@extends('layouts.frontend')

@section('content')
<div class="container pb-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="contact-hero reveal mb-4">
                <span class="contact-kicker">Get in touch</span>
                <h1>We’re here to help with anything in the store</h1>
                <p>
                    Questions about products, offers, coupons, or your order? Send us a message and our team will get back to you.
                </p>
            </div>
        </div>
    </div>

    <div class="row g-4 justify-content-center">
        <div class="col-lg-5">
            <div class="contact-info-card reveal h-100">
                <h4 class="fw-bold mb-4">Contact Details</h4>
                <div class="contact-info-item">
                    <div class="contact-info-icon"><i class="bi bi-envelope"></i></div>
                    <div>
                        <div class="fw-bold">Email</div>
                        <div class="text-muted">support@discountstore.com</div>
                    </div>
                </div>
                <div class="contact-info-item">
                    <div class="contact-info-icon"><i class="bi bi-telephone"></i></div>
                    <div>
                        <div class="fw-bold">Phone</div>
                        <div class="text-muted">+1 (555) 120-4455</div>
                    </div>
                </div>
                <div class="contact-info-item">
                    <div class="contact-info-icon"><i class="bi bi-geo-alt"></i></div>
                    <div>
                        <div class="fw-bold">Store Support</div>
                        <div class="text-muted">Available 24/7 for store and order help</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="contact-form-card reveal h-100">
                <h4 class="fw-bold mb-4">Send a Message</h4>
                <form>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Your Name</label>
                        <input type="text" class="form-control form-control-lg" placeholder="Enter your name">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Email Address</label>
                        <input type="email" class="form-control form-control-lg" placeholder="Enter your email">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Subject</label>
                        <input type="text" class="form-control form-control-lg" placeholder="Order, coupon, product, etc.">
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Message</label>
                        <textarea class="form-control form-control-lg" rows="5" placeholder="Tell us how we can help."></textarea>
                    </div>
                    <button type="button" class="btn btn-primary btn-lg w-100">Send Message</button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .contact-hero {
        padding: 2rem 2.2rem;
        border-radius: 28px;
        background: linear-gradient(135deg, #2f6cff 0%, #1f56da 100%);
        color: #fff;
        box-shadow: 0 24px 60px rgba(34, 74, 168, 0.2);
    }

    .contact-kicker {
        display: inline-block;
        margin-bottom: 0.9rem;
        padding: 0.35rem 0.75rem;
        border-radius: 999px;
        background: rgba(255,255,255,0.14);
        font-size: 0.75rem;
        letter-spacing: 1.5px;
        font-weight: 800;
        text-transform: uppercase;
    }

    .contact-hero h1 {
        color: #fff;
        font-size: clamp(2rem, 4vw, 3.4rem);
        line-height: 1.05;
        margin-bottom: 0.8rem;
        max-width: 12ch;
    }

    .contact-hero p {
        color: rgba(255,255,255,0.9);
        max-width: 52rem;
        margin-bottom: 0;
        line-height: 1.7;
    }

    .contact-info-card,
    .contact-form-card {
        background: #fff;
        border-radius: 24px;
        padding: 1.6rem;
        border: 1px solid rgba(16, 35, 63, 0.09);
        box-shadow: 0 12px 30px rgba(16, 35, 63, 0.08);
    }

    .contact-info-item {
        display: flex;
        gap: 1rem;
        align-items: center;
        padding: 1rem 0;
        border-bottom: 1px solid rgba(16, 35, 63, 0.08);
    }

    .contact-info-item:last-child {
        border-bottom: 0;
        padding-bottom: 0;
    }

    .contact-info-icon {
        width: 46px;
        height: 46px;
        border-radius: 50%;
        display: grid;
        place-items: center;
        color: #fff;
        background: linear-gradient(135deg, #4a83ff, #2663eb);
        flex: 0 0 auto;
    }

    .form-control-lg {
        border-radius: 14px;
        border-color: rgba(16, 35, 63, 0.14);
    }

    .form-control-lg:focus {
        border-color: rgba(15, 98, 254, 0.5);
        box-shadow: 0 0 0 0.2rem rgba(15, 98, 254, 0.12);
    }
</style>
@endsection
