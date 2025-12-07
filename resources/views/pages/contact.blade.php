@extends('layouts.app')

@section('title', 'تواصل معانا - Swape It')

@section('styles')
<style>
    .contact-section {
        padding: 80px 0;
        background: #fff;
    }
    
    .contact-info-card {
        background: linear-gradient(135deg, var(--dark-color), var(--primary-color));
        border-radius: 20px;
        padding: 40px;
        color: #fff;
        height: 100%;
    }
    
    .contact-info-card h3 {
        font-size: 1.8rem;
        font-weight: 700;
        margin-bottom: 20px;
    }
    
    .contact-info-card p {
        opacity: 0.9;
        margin-bottom: 30px;
        line-height: 1.8;
    }
    
    .contact-item {
        display: flex;
        align-items: flex-start;
        gap: 15px;
        margin-bottom: 25px;
    }
    
    .contact-item i {
        width: 50px;
        height: 50px;
        background: rgba(255,255,255,0.1);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        flex-shrink: 0;
    }
    
    .contact-item h4 {
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: 5px;
        opacity: 0.8;
    }
    
    .contact-item p {
        margin: 0;
        font-size: 1.1rem;
        opacity: 1;
    }
    
    .social-contact {
        margin-top: 30px;
        padding-top: 30px;
        border-top: 1px solid rgba(255,255,255,0.2);
    }
    
    .social-contact h4 {
        margin-bottom: 20px;
        font-weight: 600;
    }
    
    .social-contact a {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 50px;
        height: 50px;
        background: rgba(255,255,255,0.1);
        border-radius: 50%;
        color: #fff;
        margin-left: 10px;
        transition: all 0.3s ease;
        font-size: 1.3rem;
    }
    
    .social-contact a:hover {
        background: var(--accent-color);
        transform: translateY(-3px);
    }
    
    .contact-form-card {
        background: #fff;
        border-radius: 20px;
        padding: 40px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
    }
    
    .contact-form-card h3 {
        font-size: 1.8rem;
        font-weight: 700;
        color: var(--dark-color);
        margin-bottom: 10px;
    }
    
    .contact-form-card > p {
        color: #666;
        margin-bottom: 30px;
    }
    
    .form-group {
        margin-bottom: 25px;
    }
    
    .form-group label {
        font-weight: 600;
        color: var(--dark-color);
        margin-bottom: 10px;
        display: block;
    }
    
    .form-control {
        border: 2px solid #eee;
        border-radius: 12px;
        padding: 15px 20px;
        font-size: 1rem;
        transition: all 0.3s ease;
    }
    
    .form-control:focus {
        border-color: var(--accent-color);
        box-shadow: 0 0 0 4px rgba(87, 197, 182, 0.15);
    }
    
    textarea.form-control {
        min-height: 150px;
        resize: vertical;
    }
    
    .btn-submit {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        color: #fff;
        font-weight: 700;
        padding: 15px 40px;
        border-radius: 50px;
        border: none;
        font-size: 1.1rem;
        transition: all 0.3s ease;
        width: 100%;
    }
    
    .btn-submit:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 40px rgba(21, 152, 149, 0.4);
        color: #fff;
    }
    
    .map-section {
        padding: 0 0 80px;
        background: #fff;
    }
    
    .map-container {
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
    }
    
    .map-container iframe {
        width: 100%;
        height: 400px;
        border: none;
    }
    
    .quick-contact {
        padding: 80px 0;
        background: var(--light-bg);
    }
    
    .quick-card {
        background: #fff;
        border-radius: 20px;
        padding: 40px;
        text-align: center;
        height: 100%;
        transition: all 0.3s ease;
    }
    
    .quick-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    }
    
    .quick-card i {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, var(--secondary-color), var(--accent-color));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        font-size: 2rem;
        color: #fff;
    }
    
    .quick-card h4 {
        font-weight: 700;
        color: var(--dark-color);
        margin-bottom: 10px;
    }
    
    .quick-card p {
        color: #666;
        margin-bottom: 20px;
    }
    
    .quick-card a {
        color: var(--primary-color);
        font-weight: 600;
        text-decoration: none;
        font-size: 1.1rem;
    }
    
    .quick-card a:hover {
        color: var(--secondary-color);
    }
</style>
@endsection

@section('content')
<!-- Page Header -->
<section class="page-header">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">الرئيسية</a></li>
                <li class="breadcrumb-item active">تواصل معانا</li>
            </ol>
        </nav>
        <h1>تواصل معانا</h1>
        <p>نحن هنا لمساعدتك في أي وقت</p>
    </div>
</section>

<!-- Quick Contact -->
<section class="quick-contact">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4">
                <div class="quick-card">
                    <i class="bi bi-telephone"></i>
                    <h4>اتصل بينا</h4>
                    <p>فريقنا جاهز للرد على استفساراتك</p>
                    <a href="tel:01000000000">01000000000</a>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="quick-card">
                    <i class="bi bi-whatsapp"></i>
                    <h4>واتساب</h4>
                    <p>راسلنا على الواتساب في أي وقت</p>
                    <a href="https://wa.me/201000000000">01000000000</a>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="quick-card">
                    <i class="bi bi-envelope"></i>
                    <h4>البريد الإلكتروني</h4>
                    <p>ابعتلنا إيميل وهنرد عليك</p>
                    <a href="mailto:info@swapeit.com">info@swapeit.com</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Contact Section -->
<section class="contact-section">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-5">
                <div class="contact-info-card">
                    <h3>معلومات التواصل</h3>
                    <p>
                        فريقنا موجود دايماً لمساعدتك. 
                        تواصل معانا بأي طريقة تناسبك.
                    </p>
                    
                    <div class="contact-item">
                        <i class="bi bi-geo-alt"></i>
                        <div>
                            <h4>العنوان</h4>
                            <p>القاهرة، مصر - شارع التحرير</p>
                        </div>
                    </div>
                    
                    <div class="contact-item">
                        <i class="bi bi-telephone"></i>
                        <div>
                            <h4>التليفون</h4>
                            <p>01000000000</p>
                        </div>
                    </div>
                    
                    <div class="contact-item">
                        <i class="bi bi-envelope"></i>
                        <div>
                            <h4>البريد الإلكتروني</h4>
                            <p>info@swapeit.com</p>
                        </div>
                    </div>
                    
                    <div class="contact-item">
                        <i class="bi bi-clock"></i>
                        <div>
                            <h4>مواعيد العمل</h4>
                            <p>السبت - الخميس: 9 صباحاً - 9 مساءً</p>
                        </div>
                    </div>
                    
                    <div class="social-contact">
                        <h4>تابعنا على</h4>
                        <a href="#"><i class="bi bi-facebook"></i></a>
                        <a href="#"><i class="bi bi-twitter-x"></i></a>
                        <a href="#"><i class="bi bi-instagram"></i></a>
                        <a href="#"><i class="bi bi-youtube"></i></a>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-7">
                <div class="contact-form-card">
                    <h3>ابعتلنا رسالة</h3>
                    <p>املأ النموذج وهنرد عليك في أقرب وقت</p>
                    
                    <form>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>الاسم الكامل</label>
                                    <input type="text" class="form-control" placeholder="اكتب اسمك">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>رقم الموبايل</label>
                                    <input type="tel" class="form-control" placeholder="01xxxxxxxxx">
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label>البريد الإلكتروني</label>
                            <input type="email" class="form-control" placeholder="example@email.com">
                        </div>
                        
                        <div class="form-group">
                            <label>الموضوع</label>
                            <select class="form-control">
                                <option value="">اختار الموضوع</option>
                                <option>استفسار عن تبديل عربية</option>
                                <option>طلب تقييم مجاني</option>
                                <option>شكوى أو اقتراح</option>
                                <option>استفسار عام</option>
                                <option>موضوع آخر</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label>الرسالة</label>
                            <textarea class="form-control" placeholder="اكتب رسالتك هنا..."></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-submit">
                            <i class="bi bi-send me-2"></i>
                            ابعت الرسالة
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Map Section -->
<section class="map-section">
    <div class="container">
        <div class="map-container">
            <iframe 
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3453.6661026283984!2d31.235711!3d30.044444!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMzDCsDAyJzQwLjAiTiAzMcKwMTQnMDguNiJF!5e0!3m2!1sen!2seg!4v1234567890"
                allowfullscreen="" 
                loading="lazy" 
                referrerpolicy="no-referrer-when-downgrade">
            </iframe>
        </div>
    </div>
</section>
@endsection


