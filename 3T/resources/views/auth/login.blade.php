@extends('layout.main')

@section('content')
<main class="login-form">
  <div class="container mt-4">
      <div class="row justify-content-center">
          <div class="col-md-8">
              <div class="card">
                  <div class="card-header" style="background-color: #992424">
                      <center><b style="color: aliceblue">Masuk</b></center>
                  </div>
                  <div class="card-body">

                      <form method="POST" action="{{ route('login.sendCode') }}">
                          @csrf
                          <div class="form-group row">
                              <label for="student_id" class="col-md-4 col-form-label text-md-right">ID:</label>
                              <div class="col-md-6">
                                  <input type="text" id="student_id" class="form-control" 
                                         placeholder="Masukkan ID Anda" name="student_id" required autofocus>
                              </div>
                          </div>  

                          <!-- Hidden field for device identifier -->
                          <input type="hidden" id="device_identifier" name="device_identifier" value="">

                          <br>
                          @if ($errors->any())
                            <div class="col-md-6 offset-md-4 alert alert-danger">
                                @foreach ($errors->all() as $error)
                                    {{ $error }}
                                @endforeach
                            </div>
                          @endif
                          
                          <div class="col-md-6 offset-md-4 mt-3">
                              <button type="submit" class="btn btn-primary">Masuk</button>
                          </div>
                      </form>
                        
                  </div>
              </div>
          </div>
      </div>
  </div>
</main>

<!-- JavaScript for Device Identifier -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        let deviceId = localStorage.getItem('device_id');

        // If device ID doesn't exist, generate a new one
        if (!deviceId) {
            deviceId = 'device-' + Math.random().toString(36).substring(2) + Date.now();
            localStorage.setItem('device_id', deviceId);
        }

        // Set the hidden input value to the device ID
        document.getElementById('device_identifier').value = deviceId;
    });
</script>
@endsection
