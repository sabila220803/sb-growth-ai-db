import cv2
import mediapipe as mp
import numpy as np
from flask import Flask, request, jsonify
import base64

app = Flask(__name__)

# --- INISIALISASI MEDIAPIPE ---
mp_pose = mp.solutions.pose
pose = mp_pose.Pose(static_image_mode=True, min_detection_confidence=0.5)
mp_drawing = mp.solutions.drawing_utils

def detect_a4_paper(image):
    gray = cv2.cvtColor(image, cv2.COLOR_BGR2GRAY)
    blurred = cv2.GaussianBlur(gray, (5, 5), 0)
    edges = cv2.Canny(blurred, 50, 150)
    
    # Dilate agar garis putus-putus menyambung
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

@app.route('/', methods=['GET'])
def home():
    return "AI Service (With Debug Visualizer) Ready."

@app.route('/predict', methods=['POST'])
def predict():
    if 'image' not in request.files:
        return jsonify({"error": "Tidak ada gambar"}), 400

    file = request.files['image']
    npimg = np.frombuffer(file.read(), np.uint8)
    image = cv2.imdecode(npimg, cv2.IMREAD_COLOR)
    
    # Copy gambar untuk dicoret-coret (Debug View)
    debug_image = image.copy()

    # --- 1. DETEKSI KERTAS ---
    a4_contour = detect_a4_paper(image)
    if a4_contour is None:
        return jsonify({"error": "Gagal: Kertas A4 tidak ditemukan."}), 400

    # GAMBAR KOTAK DI KERTAS (Warna Hijau)
    cv2.drawContours(debug_image, [a4_contour], -1, (0, 255, 0), 3)

    rect = cv2.minAreaRect(a4_contour)
    (w, h) = rect[1]
    paper_pixel_len = max(w, h)
    
    A4_REAL_HEIGHT_CM = 29.7
    pixel_per_cm = paper_pixel_len / A4_REAL_HEIGHT_CM

    # --- 2. DETEKSI TUBUH ---
    image_rgb = cv2.cvtColor(image, cv2.COLOR_BGR2RGB)
    results = pose.process(image_rgb)

    if not results.pose_landmarks:
        return jsonify({"error": "Gagal: Tubuh tidak terdeteksi."}), 400

    # GAMBAR RANGKA TUBUH (Warna Merah)
    mp_drawing.draw_landmarks(
        debug_image, 
        results.pose_landmarks, 
        mp_pose.POSE_CONNECTIONS,
        mp_drawing.DrawingSpec(color=(0,0,255), thickness=2, circle_radius=2),
        mp_drawing.DrawingSpec(color=(0,255,0), thickness=2, circle_radius=2)
    )

    landmarks = results.pose_landmarks.landmark
    h_img, w_img, _ = image.shape

    y_eye = (landmarks[mp_pose.PoseLandmark.LEFT_EYE.value].y + landmarks[mp_pose.PoseLandmark.RIGHT_EYE.value].y) / 2
    y_heel = (landmarks[mp_pose.PoseLandmark.LEFT_HEEL.value].y + landmarks[mp_pose.PoseLandmark.RIGHT_HEEL.value].y) / 2

    y_eye_px = int(y_eye * h_img)
    y_heel_px = int(y_heel * h_img)
    top_head_px = int(y_eye_px - (h_img * 0.05))

    # GAMBAR GARIS TINGGI BADAN (Warna Biru)
    # Garis dari Kepala ke Kaki
    cv2.line(debug_image, (int(w_img/2), top_head_px), (int(w_img/2), y_heel_px), (255, 0, 0), 4)
    # Garis batas atas (Kepala)
    cv2.line(debug_image, (int(w_img/2)-50, top_head_px), (int(w_img/2)+50, top_head_px), (255, 0, 0), 2)
    # Garis batas bawah (Kaki)
    cv2.line(debug_image, (int(w_img/2)-50, y_heel_px), (int(w_img/2)+50, y_heel_px), (255, 0, 0), 2)

    # Hitung Tinggi
    body_height_px = abs(y_heel_px - top_head_px)
    body_height_cm = body_height_px / pixel_per_cm

    # --- 3. KONVERSI GAMBAR DEBUG JADI TEKS (BASE64) ---
    # Agar bisa dikirim balik ke Laravel lewat JSON
    _, buffer = cv2.imencode('.jpg', debug_image)
    debug_image_base64 = base64.b64encode(buffer).decode('utf-8')

    return jsonify({
        "status": "success",
        "pesan": "Berhasil",
        "tinggi_badan": round(body_height_cm, 2),
        "debug_image": debug_image_base64  # Ini data gambarnya
    })

if __name__ == '__main__':
    app.run(debug=True, port=5000)