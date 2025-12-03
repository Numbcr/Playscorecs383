@extends('layouts.app')

@section('title', 'About Us - PlayScore')

@section('content')
<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card" style="background-color: #2d2d2d; border: none; border-radius: 15px; padding: 2rem;">
                <h1 class="text-center mb-4" style="color: #ffffff; font-weight: 600;">About PlayScore</h1>
                
                <div class="mb-4">
                    <h3 style="color: #007bff; font-size: 1.5rem; margin-bottom: 1rem;">Our Mission</h3>
                    <p style="color: #e0e0e0; line-height: 1.8; font-size: 1.1rem;">
                        PlayScore is dedicated to providing gamers with honest, detailed, and professional game reviews. 
                        We believe that every gamer deserves trustworthy information before investing their time and money 
                        in a new game.
                    </p>
                </div>

                <div class="mb-4">
                    <h3 style="color: #007bff; font-size: 1.5rem; margin-bottom: 1rem;">What We Offer</h3>
                    <ul style="color: #e0e0e0; line-height: 2; font-size: 1.1rem;">
                        <li><strong>Professional Reviews:</strong> In-depth analysis of the latest and greatest games</li>
                        <li><strong>AI-Powered Recommendations:</strong> Personalized game suggestions based on your preferences</li>
                        <li><strong>Bilingual Support:</strong> Content available in English and Arabic</li>
                        <li><strong>Community Engagement:</strong> Interactive comments and discussions</li>
                        <li><strong>Up-to-Date Information:</strong> Latest game releases and updates</li>
                    </ul>
                </div>

                <div class="mb-4">
                    <h3 style="color: #007bff; font-size: 1.5rem; margin-bottom: 1rem;">Our Values</h3>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div style="background-color: #373737; padding: 1.5rem; border-radius: 10px;">
                                <h5 style="color: #ffffff; margin-bottom: 0.5rem;">
                                    <i class="fas fa-check-circle" style="color: #28a745;"></i> Honesty
                                </h5>
                                <p style="color: #cccccc; margin: 0;">Transparent and unbiased reviews</p>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div style="background-color: #373737; padding: 1.5rem; border-radius: 10px;">
                                <h5 style="color: #ffffff; margin-bottom: 0.5rem;">
                                    <i class="fas fa-users" style="color: #007bff;"></i> Community
                                </h5>
                                <p style="color: #cccccc; margin: 0;">Building a gaming community</p>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div style="background-color: #373737; padding: 1.5rem; border-radius: 10px;">
                                <h5 style="color: #ffffff; margin-bottom: 0.5rem;">
                                    <i class="fas fa-chart-line" style="color: #ffc107;"></i> Quality
                                </h5>
                                <p style="color: #cccccc; margin: 0;">High-quality content always</p>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div style="background-color: #373737; padding: 1.5rem; border-radius: 10px;">
                                <h5 style="color: #ffffff; margin-bottom: 0.5rem;">
                                    <i class="fas fa-rocket" style="color: #dc3545;"></i> Innovation
                                </h5>
                                <p style="color: #cccccc; margin: 0;">Embracing new technologies</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <h3 style="color: #007bff; font-size: 1.5rem; margin-bottom: 1rem;">Why Choose PlayScore?</h3>
                    <p style="color: #e0e0e0; line-height: 1.8; font-size: 1.1rem;">
                        Unlike other gaming platforms, PlayScore combines professional game reviews with cutting-edge 
                        AI technology to provide personalized recommendations. Our bilingual support makes us accessible 
                        to both English and Arabic-speaking gamers, filling a gap in the gaming review market.
                    </p>
                    <p style="color: #e0e0e0; line-height: 1.8; font-size: 1.1rem;">
                        Whether you're a casual gamer looking for your next adventure or a hardcore enthusiast seeking 
                        detailed analysis, PlayScore has something for everyone.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
