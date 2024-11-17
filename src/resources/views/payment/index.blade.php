@extends('layouts.app')

@section('content')
<div class="container">
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    <div class="p-5">
        <div class="col-6 card">
            <div class="card-header">Stripe決済</div>
            <div class="card-body">
                <form id="payment-form" action="{{ route('payment.process') }}" method="POST">
                    @csrf
                    <div>
                        <label for="course">コース選択</label>
                        <select id="course" name="course" class="form-control" required>
                            <option value="10000">松 - 10000円</option>
                            <option value="8000">竹 - 8000円</option>
                            <option value="5000">梅 - 5000円</option>
                        </select>
                    </div>
                    <div>
                        <label for="guests">予約人数</label>
                        <input type="number" id="guests" name="guests" class="form-control" required>
                    </div>
                    <div>
                        <label for="card-element">クレジットカード情報</label>
                        <div id="card-element" class="form-control"></div>
                    </div>
                    <div id="card-errors" class="text-danger"></div>
                    <button class="mt-3 btn btn-primary">支払い</button>
                    <input type="hidden" name="payment_method" id="payment_method">
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://js.stripe.com/v3/"></script>
<script>
    const stripe = Stripe('{{ config('cashier.key') }}');
    const elements = stripe.elements();
    const cardElement = elements.create('card');
    cardElement.mount('#card-element');

    const form = document.getElementById('payment-form');
    form.addEventListener('submit', async (event) => {
        event.preventDefault();

        const { paymentMethod, error } = await stripe.createPaymentMethod(
            'card', cardElement, {
                billing_details: { name: '{{ auth()->user()->name }}' }
            }
        );

        if (error) {
            document.getElementById('card-errors').textContent = error.message;
        } else {
            document.getElementById('payment_method').value = paymentMethod.id;
            form.submit();
        }
    });
</script>
@endsection