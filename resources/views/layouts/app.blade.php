<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>@yield('title', 'Inventory') - {{ config('app.name') }}</title>
<style>
*{box-sizing:border-box}body{font-family:system-ui,Arial,sans-serif;margin:0;background:#f4f6f9;color:#222}
nav{background:#1f3a5f;color:#fff;padding:12px 20px;display:flex;gap:16px;align-items:center;flex-wrap:wrap}
nav a{color:#cfe0ff;text-decoration:none}nav a:hover{color:#fff}nav .right{margin-left:auto;display:flex;gap:8px;align-items:center}
nav .linkbtn{background:none;border:0;color:#cfe0ff;cursor:pointer;padding:0;font-size:1em}
main{max-width:1000px;margin:24px auto;padding:0 16px}
h1{margin-top:0}table{width:100%;border-collapse:collapse;background:#fff}
th,td{padding:8px 10px;border-bottom:1px solid #e3e7ee;text-align:left;font-size:14px}th{background:#eef2f8}
.card{background:#fff;padding:16px;border-radius:8px;box-shadow:0 1px 3px #0001;margin-bottom:16px}
.grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:12px}
.stat b{display:block;font-size:22px;margin-top:4px}
label{display:block;margin:10px 0 4px;font-weight:600}
input,select,textarea{width:100%;padding:8px;border:1px solid #bcc5d3;border-radius:6px}
button,.btn{background:#1f3a5f;color:#fff;border:0;padding:8px 14px;border-radius:6px;cursor:pointer;text-decoration:none;display:inline-block;font-size:14px}
.btn.danger{background:#b3261e}.btn.gray{background:#6b7686}
.alert{padding:10px 14px;border-radius:6px;margin-bottom:14px}.alert.ok{background:#e1f5e5;color:#1b5e2b}
.alert.err{background:#fde7e5;color:#8a1c14}
small.error{color:#b3261e;display:block;margin-top:4px}
.filters{display:flex;gap:8px;flex-wrap:wrap;margin-bottom:12px}.filters input,.filters select{width:auto}
.low{color:#b3261e;font-weight:700}.inline{display:inline}.login{max-width:360px;margin:80px auto}
.pager{margin-top:12px;display:flex;gap:4px;flex-wrap:wrap}
.pager a,.pager span{padding:4px 10px;border:1px solid #bcc5d3;border-radius:4px;text-decoration:none;color:#1f3a5f;background:#fff}
.pager span.cur{background:#1f3a5f;color:#fff}.pager span.dis{color:#999}
</style>
</head>
<body>
@auth
    @include('layouts.navigation')
@endauth
<main>
    @if (session('success'))
        <x-alert type="ok">{{ session('success') }}</x-alert>
    @endif
    @if (session('error'))
        <x-alert type="err">{{ session('error') }}</x-alert>
    @endif

    @yield('content')
</main>
</body>
</html>
