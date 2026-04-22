{{-- Camera Modal — include on any page that needs camera photo capture --}}
<div id="cameraModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.75);z-index:9999;align-items:center;justify-content:center;">
  <div style="background:#fff;border-radius:16px;padding:24px;text-align:center;max-width:500px;width:95%;box-shadow:0 20px 60px rgba(0,0,0,.4);">
    <h6 class="fw-700 mb-3"><i class="bi bi-camera-fill text-primary me-2"></i>Take Patient Photo</h6>
    <video id="cameraVideo" autoplay playsinline style="width:100%;max-height:320px;border-radius:10px;background:#000;"></video>
    <canvas id="cameraCanvas" style="display:none;"></canvas>
    <div id="capturedPreviewWrap" style="display:none;">
      <img id="capturedImg" style="width:100%;border-radius:10px;max-height:300px;object-fit:cover;">
    </div>
    <div class="d-flex gap-2 mt-3 justify-content-center">
      <button type="button" class="btn btn-primary" id="captureBtn" onclick="capturePhoto()">
        <i class="bi bi-camera-fill me-1"></i>Capture
      </button>
      <button type="button" class="btn btn-success" id="usePhotoBtn" onclick="usePhoto()" style="display:none;">
        <i class="bi bi-check-lg me-1"></i>Use This Photo
      </button>
      <button type="button" class="btn btn-warning" id="retakeBtn" onclick="retakePhoto()" style="display:none;">
        <i class="bi bi-arrow-counterclockwise me-1"></i>Retake
      </button>
      <button type="button" class="btn btn-secondary" onclick="closeCamera()">Cancel</button>
    </div>
  </div>
</div>

@push('scripts')
<script>
let _stream = null, _captured = null;

async function openCamera() {
  document.getElementById('cameraModal').style.display = 'flex';
  document.getElementById('capturedPreviewWrap').style.display = 'none';
  document.getElementById('cameraVideo').style.display = 'block';
  document.getElementById('captureBtn').style.display = 'inline-block';
  document.getElementById('usePhotoBtn').style.display = 'none';
  document.getElementById('retakeBtn').style.display = 'none';
  try {
    _stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user', width: 640, height: 480 } });
    document.getElementById('cameraVideo').srcObject = _stream;
  } catch(e) {
    alert('Camera not accessible. Please allow camera permission and try again.');
    closeCamera();
  }
}

function capturePhoto() {
  const video = document.getElementById('cameraVideo');
  const canvas = document.getElementById('cameraCanvas');
  canvas.width = video.videoWidth || 640;
  canvas.height = video.videoHeight || 480;
  canvas.getContext('2d').drawImage(video, 0, 0);
  _captured = canvas.toDataURL('image/jpeg', 0.85);
  document.getElementById('capturedImg').src = _captured;
  document.getElementById('capturedPreviewWrap').style.display = 'block';
  document.getElementById('cameraVideo').style.display = 'none';
  document.getElementById('captureBtn').style.display = 'none';
  document.getElementById('usePhotoBtn').style.display = 'inline-block';
  document.getElementById('retakeBtn').style.display = 'inline-block';
  if(_stream) _stream.getTracks().forEach(t => t.stop());
}

function retakePhoto() {
  _captured = null;
  document.getElementById('capturedPreviewWrap').style.display = 'none';
  document.getElementById('cameraVideo').style.display = 'block';
  document.getElementById('captureBtn').style.display = 'inline-block';
  document.getElementById('usePhotoBtn').style.display = 'none';
  document.getElementById('retakeBtn').style.display = 'none';
  navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user' } }).then(s => {
    _stream = s; document.getElementById('cameraVideo').srcObject = s;
  });
}

function usePhoto() {
  if (!_captured) return;
  document.getElementById('photoData').value = _captured;
  const preview = document.getElementById('photoPreview');
  if(preview){ preview.src = _captured; preview.style.display = 'block'; }
  const noPhoto = document.getElementById('noPhoto');
  if(noPhoto) noPhoto.style.display = 'none';
  closeCamera();
}

function closeCamera() {
  if(_stream) { _stream.getTracks().forEach(t => t.stop()); _stream = null; }
  document.getElementById('cameraModal').style.display = 'none';
}
</script>
@endpush
