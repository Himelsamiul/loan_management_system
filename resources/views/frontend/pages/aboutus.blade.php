@extends('frontend.master')

@section('content')
<style>
    /* Slide-in animations */
    .slide-top {
        opacity: 0;
        transform: translateY(-50px);
        animation: slideTop 1s forwards;
    }
    .slide-left {
        opacity: 0;
        transform: translateX(-50px);
        animation: slideLeft 1s forwards;
    }
    .slide-right {
        opacity: 0;
        transform: translateX(50px);
        animation: slideRight 1s forwards;
    }
    @keyframes slideTop {
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes slideLeft {
        to { opacity: 1; transform: translateX(0); }
    }
    @keyframes slideRight {
        to { opacity: 1; transform: translateX(0); }
    }
</style>

<div class="container py-5">

    <!-- Hero Section -->
    <div class="text-center mb-5 py-5 rounded" style="background: linear-gradient(135deg, #eceeecff, #f9fcf9ff); color: #3f3810ff;">
        <h1 class="fw-bold display-4 slide-top">Ababil Finance Company</h1>
        <p class="lead slide-top" style="animation-delay: 0.3s;">Your Trusted Bangladeshi Financial Partner</p>
    </div>

    <!-- About Section -->
    <div class="row align-items-center mb-5">
        <div class="col-md-6 slide-left">
            <img src="a.png" 
                 class="img-fluid rounded shadow-sm" 
                 alt="Ababil Finance Office">
        </div>
        <div class="col-md-6 slide-right">
            <h2 class="fw-bold mb-3">Who We Are</h2>
            <p>
                Ababil Finance Company is committed to providing reliable and innovative financial solutions
                across Bangladesh. Our focus is transparency, efficiency, and customer satisfaction, making finance simple and accessible for everyone.
            </p>
        </div>
    </div>

    <!-- Mission & Vision Section -->
    <div class="row text-center mb-5">
        <div class="col-md-6 mb-4 slide-left">
            <div class="card shadow-sm border-0 p-4 h-100">
                <img src="https://via.placeholder.com/150?text=Mission" class="mx-auto mb-3" alt="Mission">
                <h4 class="fw-bold">Our Mission</h4>
                <p>To empower our Bangladeshi clients with trusted financial solutions and personalized services.</p>
            </div>
        </div>
        <div class="col-md-6 mb-4 slide-right">
            <div class="card shadow-sm border-0 p-4 h-100">
                <img src="https://via.placeholder.com/150?text=Vision" class="mx-auto mb-3" alt="Vision">
                <h4 class="fw-bold">Our Vision</h4>
                <p>To become the leading finance company in Bangladesh known for reliability, innovation, and customer trust.</p>
            </div>
        </div>
    </div>

    <!-- Team Section -->
    <div class="text-center mb-4 slide-top">
        <h2 class="fw-bold mb-3">Meet Our Team</h2>
        <p class="text-muted">Professional, dedicated, and ready to help you succeed.</p>
    </div>
    <div class="row text-center">
        <div class="col-md-4 mb-4 slide-left">
            <img src="https://via.placeholder.com/200?text=CEO" class="rounded-circle shadow-sm mb-2" alt="CEO">
            <h5 class="fw-bold">Md. Hasan</h5>
            <p class="text-muted">CEO</p>
        </div>
        <div class="col-md-4 mb-4 slide-top">
            <img src="https://via.placeholder.com/200?text=CFO" class="rounded-circle shadow-sm mb-2" alt="CFO">
            <h5 class="fw-bold">Ayesha Rahman</h5>
            <p class="text-muted">CFO</p>
        </div>
        <div class="col-md-4 mb-4 slide-right">
            <img src="https://via.placeholder.com/200?text=Manager" class="rounded-circle shadow-sm mb-2" alt="Manager">
            <h5 class="fw-bold">Sabbir Ahmed</h5>
            <p class="text-muted">Operations Manager</p>
        </div>
    </div>

</div>

<script>
    // Trigger animations when elements appear on screen
    const observer = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if(entry.isIntersecting){
                entry.target.style.animationPlayState = 'running';
            }
        });
    }, { threshold: 0.1 });

    document.querySelectorAll('.slide-top, .slide-left, .slide-right').forEach(el => {
        el.style.animationPlayState = 'paused';
        observer.observe(el);
    });
</script>
@endsection
