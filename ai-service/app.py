import cv2
import mediapipe as mp
import numpy as np
from flask import Flask, request, jsonify
import base64
import math

app = Flask(__name__)

# --- INISIALISASI MEDIAPIPE ---
mp_pose = mp.solutions.pose
pose = mp_pose.Pose(static_image_mode=True, min_detection_confidence=0.5)
mp_drawing = mp.solutions.drawing_utils

def detect_a4_paper(image):
    gray = cv2.cvtColor(image, cv2.COLOR_BGR2GRAY)
    blurred = cv2.GaussianBlur(gray, (5, 5), 0)
    edges = cv2.Canny(blurred, 50, 150)
    
    kernel = np.ones((5,5), np.uint8)
    edges = cv2.dilate(edges, kernel, iterations=1)

    contours, _ = cv2.findContours(edges, cv2.RETR_EXTERNAL, cv2.CHAIN_APPROX_SIMPLE)
    
    largest_contour = None
    max_area = 0

    for cnt in contours:
        area = cv2.contourArea(cnt)
        if area > 1000:
            peri = cv2.arcLength(cnt, True)
            approx = cv2.approxPolyDP(cnt, 0.02 * peri, True)
            if len(approx) == 4 and area > max_area:
                largest_contour = approx
                max_area = area

    return largest_contour

# --- FUNGSI BARU: HITUNG JARAK EUCLIDEAN ---
def calculate_distance(point1, point2):
    return math.sqrt((point2[0] - point1[0])**2 + (point2[1] - point1[1])**2)

# --- FUNGSI BARU: ALGORITMA SEGMENTED HEIGHT ---
def get_segmented_height(landmarks, width, height, debug_image):
    # Ambil koordinat landmark penting
    # 0: Nose, 11/12: Shoulders, 23/24: Hips, 25/26: Knees, 27/28: Ankles
    
    def get_coords(landmark_idx):
        return (int(landmarks[landmark_idx].x * width), int(landmarks[landmark_idx].y * height))

    nose = get_coords(0)
    
    shoulder_l = get_coords(11)
    shoulder_r = get_coords(12)
    mid_shoulder = (int((shoulder_l[0]+shoulder_r[0])/2), int((shoulder_l[1]+shoulder_r[1])/2))
    
    hip_l = get_coords(23)
    hip_r = get_coords(24)
    mid_hip = (int((hip_l[0]+hip_r[0])/2), int((hip_l[1]+hip_r[1])/2))
    
    knee_l = get_coords(25)
    knee_r = get_coords(26)
    mid_knee = (int((knee_l[0]+knee_r[0])/2), int((knee_l[1]+knee_r[1])/2))
    
    ankle_l = get_coords(27)
    ankle_r = get_coords(28)
    mid_ankle = (int((ankle_l[0]+ankle_r[0])/2), int((ankle_l[1]+ankle_r[1])/2))

    # --- PERHITUNGAN SEGMEN TULANG ---
    
    # 1. Kepala (Estimasi dari Hidung ke Bahu Tengah x Faktor Koreksi Kepala)
    # Faktor 1.6 - 1.8 biasanya digunakan untuk estimasi puncak kepala dari hidung
    dist_head = calculate_distance(nose, mid_shoulder) * 1.5
    
    # 2. Torso (Bahu Tengah ke Pinggul Tengah) - Tulang Belakang
    dist_torso = calculate_distance(mid_shoulder, mid_hip)
    
    # 3. Paha (Pinggul Tengah ke Lutut Tengah) - Femur
    # Menggunakan rata-rata kiri kanan untuk stabilitas
    dist_thigh_l = calculate_distance(hip_l, knee_l)
    dist_thigh_r = calculate_distance(hip_r, knee_r)
    dist_thigh = (dist_thigh_l + dist_thigh_r) / 2
    
    # 4. Betis (Lutut Tengah ke Pergelangan Kaki Tengah) - Tibia
    dist_shin_l = calculate_distance(knee_l, ankle_l)
    dist_shin_r = calculate_distance(knee_r, ankle_r)
    dist_shin = (dist_shin_l + dist_shin_r) / 2

    # Total Tinggi dalam PIKSEL
    total_height_px = dist_head + dist_torso + dist_thigh + dist_shin

    # --- VISUALISASI DEBUG (ALUR PENGUKURAN) ---
    # Gambar garis segmen yang dihitung (Warna Cyan)
    cv2.line(debug_image, mid_shoulder, mid_hip, (255, 255, 0), 4) # Torso
    cv2.line(debug_image, mid_hip, mid_knee, (255, 255, 0), 4)     # Paha
    cv2.line(debug_image, mid_knee, mid_ankle, (255, 255, 0), 4)   # Betis
    
    # Garis Kepala (Putus-putus simulasi)
    top_head_est = (mid_shoulder[0], int(mid_shoulder[1] - dist_head))
    cv2.line(debug_image, mid_shoulder, top_head_est, (0, 255, 255), 2)
    
    return total_height_px

@app.route('/', methods=['GET'])
def home():
    return "AI Service (Segmented Skeleton Algorithm) Ready."

@app.route('/predict', methods=['POST'])
def predict():
    if 'image' not in request.files:
        return jsonify({"error": "Tidak ada gambar"}), 400

    file = request.files['image']
    npimg = np.frombuffer(file.read(), np.uint8)
    image = cv2.imdecode(npimg, cv2.IMREAD_COLOR)
    
    # Copy gambar untuk dicoret-coret (Debug View)
    debug_image = image.copy()

    # --- 1. DETEKSI KERTAS A4 (KALIBRASI) ---
    a4_contour = detect_a4_paper(image)
    if a4_contour is None:
        return jsonify({"error": "Gagal: Kertas A4 tidak ditemukan. Pastikan kertas terlihat jelas."}), 400

    # GAMBAR KOTAK DI KERTAS (Warna Hijau)
    cv2.drawContours(debug_image, [a4_contour], -1, (0, 255, 0), 3)

    rect = cv2.minAreaRect(a4_contour)
    (w, h) = rect[1]
    paper_pixel_len = max(w, h)
    
    A4_REAL_HEIGHT_CM = 29.7
    pixel_per_cm = paper_pixel_len / A4_REAL_HEIGHT_CM

    # --- 2. DETEKSI TUBUH & HITUNG TINGGI (METODE BARU) ---
    image_rgb = cv2.cvtColor(image, cv2.COLOR_BGR2RGB)
    results = pose.process(image_rgb)

    if not results.pose_landmarks:
        return jsonify({"error": "Gagal: Tubuh tidak terdeteksi."}), 400

    # Gambar Skeleton Standar MediaPipe (Merah)
    mp_drawing.draw_landmarks(
        debug_image, 
        results.pose_landmarks, 
        mp_pose.POSE_CONNECTIONS,
        mp_drawing.DrawingSpec(color=(0,0,255), thickness=1, circle_radius=1),
        mp_drawing.DrawingSpec(color=(0,255,0), thickness=1, circle_radius=1)
    )

    h_img, w_img, _ = image.shape
    
    # PANGGIL FUNGSI BARU DI SINI
    height_px = get_segmented_height(results.pose_landmarks.landmark, w_img, h_img, debug_image)
    
    # Konversi ke CM
    body_height_cm = height_px / pixel_per_cm

    # Tulis Hasil di Gambar Debug
    cv2.putText(debug_image, f"Tinggi: {round(body_height_cm, 1)} cm", (50, 50), 
                cv2.FONT_HERSHEY_SIMPLEX, 1.5, (255, 0, 0), 3)

    # --- 3. KONVERSI GAMBAR DEBUG JADI BASE64 ---
    _, buffer = cv2.imencode('.jpg', debug_image)
    debug_image_base64 = base64.b64encode(buffer).decode('utf-8')

    return jsonify({
        "status": "success",
        "pesan": "Berhasil",
        "tinggi_badan": round(body_height_cm, 2),
        "debug_image": debug_image_base64
    })

if __name__ == '__main__':
    app.run(debug=True, port=5000)