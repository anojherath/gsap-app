<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Green Sun Agri Products</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet" />
    <style>
        /* Styles remain the same as your template */
        * { margin:0; padding:0; box-sizing:border-box; font-family:'Inter', sans-serif; }
        html, body { height: 100%; }
        body { background: url('{{ asset("images/login.jpg") }}') no-repeat center center fixed; background-size: cover; position: relative; color: white; }
        .overlay { position:absolute; top:0; left:0; right:0; bottom:0; background: rgba(0,0,0,0.6); z-index:1; }
        .top-left-logo { position:absolute; top:20px; left:30px; display:flex; flex-direction:row; align-items:center; z-index:3; }
        .top-left-logo img { width:170px; height:auto; object-fit:contain; margin-right:15px; }
        .company-name { font-size:60px; font-weight:700; text-shadow:1px 1px 4px rgba(0,0,0,0.3); }
        .green { color:#064e3b; } .sun { color:#c2410c; }
        .container { display:flex; height:100vh; width:100%; position:relative; z-index:2; padding:0 5%; align-items:center; justify-content:space-between; gap:20px; }
        .left-content { max-width:45%; display:flex; flex-direction:column; justify-content:center; }
        .headline { font-size:48px; font-weight:800; color:#ffffff; margin-bottom:10px; text-shadow:1px 1px 6px rgba(0,0,0,0.2); }
        .left-content p { font-size:18px; line-height:1.6; color:#dbeafe; }
        .form-wrapper { background: rgba(255,255,255,0.95); padding:40px; border-radius:12px; max-width:480px; width:100%; color:#1e293b; box-shadow:0 10px 30px rgba(0,0,0,0.15); overflow-y:auto; max-height:80vh; }
        .form-wrapper h2 { margin-bottom:20px; font-weight:700; font-size:24px; color:#111827; }
        dl { display:grid; grid-template-columns:1fr 1fr; gap:16px 24px; color:#374151; }
        dt { font-weight:600; color:#065f46; }
        dd { background:#d0ebff; border-radius:8px; padding:10px 14px; font-weight:500; word-wrap:break-word; }
        .back-button { margin-top:30px; display:inline-block; background-color:#065f46; color:white; font-weight:600; padding:12px 24px; border-radius:12px; box-shadow:0 6px 15px rgba(6,95,70,0.4); text-decoration:none; transition: background-color 0.3s ease; }
        .back-button:hover { background-color:#16a34a; }
        @media(max-width:768px){ .container{flex-direction:column; justify-content:center; text-align:center; gap:40px;} .left-content{max-width:100%; align-items:center;} .form-wrapper{max-width:90%;} .headline{font-size:36px;} .top-left-logo{flex-direction:column; align-items:center; top:10px; left:50%; transform:translateX(-50%); text-align:center;} .top-left-logo img{margin-right:0; margin-bottom:10px;} .company-name{font-size:36px;} dl{grid-template-columns:1fr;} }
    </style>
</head>
<body>
    <div class="overlay"></div>

    <div class="top-left-logo">
        <img src="{{ asset('images/logo.png') }}" alt="Logo" />
        <div class="company-name">
            <span class="green">Green</span> <span class="sun">Sun</span> <span class="green">Agri Products</span>
        </div>
    </div>

    <div class="container">
        <div class="left-content">
            <div class="headline">Product Life Cycle Details</div>
            <p>Here is the detailed information about the purchased product</p>
        </div>

        <div class="form-wrapper">
            <h2>Product Information</h2>

            <dl>
                @php
                    $fields = [
                        'Harvest Type' => $data->harvest_type ?? '—',
                        'Field' => $data->field ?? '—',
                        'Fertilizer Type' => $data->fertilizer_type ?? '—',
                        'Harvest Provided Date' => $data->harvest_date ? \Carbon\Carbon::parse($data->harvest_date)->format('Y-m-d H:i') : '—',
                        'Seed Provided Date' => $data->seed_provider_date ? \Carbon\Carbon::parse($data->seed_provider_date)->format('Y-m-d H:i') : '—',
                        'Fertilizer Provided Date' => $data->fertilizer_applied_date ? \Carbon\Carbon::parse($data->fertilizer_applied_date)->format('Y-m-d H:i') : '—',
                    ];
                @endphp

                @foreach ($fields as $label => $value)
                <div>
                    <dt>{{ $label }}</dt>
                    <dd>{{ $value }}</dd>
                </div>
                @endforeach
            </dl>

            <a href="{{ url()->previous() }}" class="back-button">Back</a>
        </div>
    </div>
</body>
</html>
