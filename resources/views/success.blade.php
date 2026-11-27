{{-- All this code below is maked by AI --}}
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagamento Confirmado</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            padding: 1rem;
            font-family: sans-serif;
            background-color: #f8fafc;
        }

        .payment-card {
            width: 100%;
            max-width: 480px;
            padding: 3rem 2rem;
            text-align: center;
            background-color: white;
            border: 1px solid #e2e8f0;
            border-radius: 1.5rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        }

        .success-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 80px;
            height: 80px;
            margin: 0 auto 1.5rem;
            color: rgb(0, 116, 212);
            font-size: 2.5rem;
            font-weight: bold;
            background-color: rgba(0, 116, 212, 0.1);
            border-radius: 50%;
        }

        h1 {
            margin: 0 0 1rem;
            color: #0f172a;
            font-size: clamp(1.5rem, 5vw, 2rem);
        }

        p {
            margin: 0 0 2rem;
            color: #64748b;
            font-size: 1rem;
            line-height: 1.6;
        }
    </style>
</head>

<body>
    <main class="payment-card">
        <div class="success-icon" aria-hidden="true">
            ✓
        </div>

        <h1>Payment completed successfully!</h1>

        <p>
            Thank you for your purchase.
            Your payment has been confirmed.
        </p>
    </main>
</body>

</html>
