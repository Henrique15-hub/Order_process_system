<?php

namespace App\Http\Controllers;

use App\Enums\PaymentStatusEnum;
use App\UseCase\Payment\PaymentCompletedUseCase;
use App\UseCase\Payment\PaymentExpiredUseCase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Webhook;
use UnexpectedValueException;

class StripeWebhookController extends Controller
{
    public function __construct(
        private PaymentCompletedUseCase $paymentCompletedUseCase,
        private PaymentExpiredUseCase $paymentExpiredUseCase,
    ) {

    }
    public function handle(Request $request)
    {
        try {
            $event = Webhook::constructEvent(
                $request->getContent(),
                $request->header('Stripe-Signature', ''),
                config('services.stripe.webhook_secret')
            );
        } catch (UnexpectedValueException | SignatureVerificationException $exception) {
            return response()->json([
                'message' => 'Invalid payload or signature'
            ], 400);
        }
        Log::info($event);
        switch ($event->type) {
            case 'checkout.session.completed':

                $session = $event->data->object;

                if ($session->payment_status === PaymentStatusEnum::Paid->value) {

                    $this->paymentCompletedUseCase->execute($session);
                }
                break;

            case 'checkout.session.expired':

                $session = $event->data->object;

                if ($session->payment_status === PaymentStatusEnum::UnPaid->value) {

                    $this->paymentExpiredUseCase->execute($session);
                }

                break;
        }

        return response()->json([
            'recived' => true
        ]);
    }
}
