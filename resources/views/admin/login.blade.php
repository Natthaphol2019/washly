<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Washly Admin</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@vite(['resources/css/app.css'])

<style>

body{
background:linear-gradient(135deg,#a8edea,#fed6e3);
min-height:100vh;
display:flex;
align-items:center;
justify-content:center;
padding:20px;
font-family:system-ui;
}

.auth-card{
width:100%;
max-width:420px;
background:white;
padding:40px;
border-radius:28px;
box-shadow:0 20px 60px rgba(0,0,0,0.15);
position:relative;
}

.logo-box{
width:80px;
height:80px;
margin:auto;
border-radius:100%;
background:#f0f9ff;
display:flex;
align-items:center;
justify-content:center;
margin-bottom:15px;
}

.logo-box img{
width:55px;
}

.input-group{
display:flex;
flex-direction:column;
gap:6px;
}

.input-field{
position:relative;
display:flex;
align-items:center;
}

.input-field input{
width:100%;
padding:14px 45px;
border-radius:999px;
border:1px solid #e5e7eb;
background:#f9fafb;
transition:all .25s;
}

.input-field input:focus{
outline:none;
border-color:#38bdf8;
background:white;
box-shadow:0 0 0 4px rgba(56,189,248,.15);
}

.input-icon{
position:absolute;
left:16px;
color:#94a3b8;
}

.password-toggle{
position:absolute;
right:16px;
cursor:pointer;
color:#94a3b8;
}

.submit-btn{
width:100%;
margin-top:10px;
padding:14px;
border-radius:999px;
background:linear-gradient(135deg,#38bdf8,#2563eb);
color:white;
font-weight:600;
font-size:16px;
display:flex;
align-items:center;
justify-content:center;
gap:8px;
transition:.25s;
}

.submit-btn:hover{
transform:translateY(-2px);
box-shadow:0 10px 20px rgba(0,0,0,.15);
}

.divider{
margin:20px 0;
display:flex;
align-items:center;
gap:10px;
color:#9ca3af;
font-size:13px;
}

.divider::before,
.divider::after{
content:"";
flex:1;
height:1px;
background:#e5e7eb;
}

.back-link{
display:flex;
align-items:center;
justify-content:center;
gap:6px;
margin-top:20px;
font-size:14px;
color:#6b7280;
transition:.2s;
}

.back-link:hover{
color:#0ea5e9;
}

</style>
</head>

<body>

<div class="auth-card">

<div class="logo-box">
<img src="{{ asset('logowashly.png') }}">
</div>

<h2 class="text-center text-2xl font-bold text-sky-600">
Admin Portal
</h2>

<p class="text-center text-sm text-gray-500 mb-6">
ระบบจัดการบริการซักผ้า Washly
</p>

<div class="divider">
เข้าสู่ระบบหลังบ้าน
</div>

<form action="{{ route('admin.login') }}" method="POST" id="loginForm" class="flex flex-col gap-4">

@csrf

<div class="input-group">

<label class="text-sm font-medium text-gray-700">
Username
</label>

<div class="input-field">

<i class="fa-solid fa-user input-icon"></i>

<input
type="text"
name="username"
placeholder="รหัสพนักงาน"
required
>

</div>
</div>


<div class="input-group">

<label class="text-sm font-medium text-gray-700">
Password
</label>

<div class="input-field">

<i class="fa-solid fa-lock input-icon"></i>

<input
type="password"
name="password"
id="password"
placeholder="••••••••"
required
>

<i class="fa-solid fa-eye password-toggle" id="togglePassword"></i>

</div>
</div>


<button class="submit-btn" id="loginBtn">

<i class="fa-solid fa-arrow-right"></i>

เข้าสู่ระบบหลังบ้าน

</button>

</form>

<a href="/login" class="back-link">

<i class="fa-solid fa-arrow-left"></i>

กลับสู่ระบบลูกค้า

</a>

</div>

<script>

const toggle = document.getElementById("togglePassword");
const password = document.getElementById("password");

toggle.addEventListener("click",()=>{

if(password.type==="password"){

password.type="text";
toggle.classList.replace("fa-eye","fa-eye-slash");

}else{

password.type="password";
toggle.classList.replace("fa-eye-slash","fa-eye");

}

});

</script>

</body>
</html>