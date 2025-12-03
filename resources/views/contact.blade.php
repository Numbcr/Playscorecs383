@extends('layouts.app')

@section('title', 'Contact Us - PlayScore')

@section('content')
<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card" style="background-color: #2d2d2d; border: none; border-radius: 15px; padding: 2rem;">
                <h1 class="text-center mb-4" style="color: #ffffff; font-weight: 600;">Contact Us</h1>
                
                <p class="text-center mb-5" style="color: #cccccc; font-size: 1.1rem;">
                    Have questions, feedback, or suggestions? We'd love to hear from you!
                </p>

                <div class="row mb-4">
                    <div class="col-md-4 mb-3 text-center">
                        <div style="background-color: #373737; padding: 2rem; border-radius: 10px; height: 100%;">
                            <div style="font-size: 3rem; color: #007bff; margin-bottom: 1rem;">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <h5 style="color: #ffffff; margin-bottom: 1rem;">Email</h5>
                            <a href="mailto:info@playscore.com" style="color: #007bff; text-decoration: none;">
                                info@playscore.com
                            </a>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3 text-center">
                        <div style="background-color: #373737; padding: 2rem; border-radius: 10px; height: 100%;">
                            <div style="font-size: 3rem; color: #007bff; margin-bottom: 1rem;">
                                <i class="fab fa-twitter"></i>
                            </div>
                            <h5 style="color: #ffffff; margin-bottom: 1rem;">Twitter</h5>
                            <a href="https://twitter.com/playscore" target="_blank" style="color: #007bff; text-decoration: none;">
                                @playscore
                            </a>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3 text-center">
                        <div style="background-color: #373737; padding: 2rem; border-radius: 10px; height: 100%;">
                            <div style="font-size: 3rem; color: #007bff; margin-bottom: 1rem;">
                                <i class="fab fa-discord"></i>
                            </div>
                            <h5 style="color: #ffffff; margin-bottom: 1rem;">Discord</h5>
                            <a href="https://discord.gg/playscore" target="_blank" style="color: #007bff; text-decoration: none;">
                                Join Our Server
                            </a>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <h3 style="color: #007bff; font-size: 1.5rem; margin-bottom: 1rem;">
                        <i class="fas fa-question-circle"></i> Frequently Asked Questions
                    </h3>
                    <div class="accordion" id="faqAccordion">
                        <div class="accordion-item" style="background-color: #373737; border: none; margin-bottom: 10px; border-radius: 8px;">
                            <h2 class="accordion-header" id="faqOne">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne"
                                        style="background-color: #373737; color: #ffffff; border-radius: 8px;">
                                    How can I submit a game review?
                                </button>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body" style="color: #cccccc;">
                                    Currently, only administrators can submit game reviews to maintain quality standards. 
                                    However, all registered users can comment on reviews and share their opinions.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item" style="background-color: #373737; border: none; margin-bottom: 10px; border-radius: 8px;">
                            <h2 class="accordion-header" id="faqTwo">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo"
                                        style="background-color: #373737; color: #ffffff; border-radius: 8px;">
                                    Is PlayScore free to use?
                                </button>
                            </h2>
                            <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body" style="color: #cccccc;">
                                    Yes! PlayScore is completely free. You can browse reviews, use our AI chatbot, and 
                                    participate in discussions without any cost.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item" style="background-color: #373737; border: none; margin-bottom: 10px; border-radius: 8px;">
                            <h2 class="accordion-header" id="faqThree">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree"
                                        style="background-color: #373737; color: #ffffff; border-radius: 8px;">
                                    How does the AI chatbot work?
                                </button>
                            </h2>
                            <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body" style="color: #cccccc;">
                                    Our AI chatbot uses Google Gemini AI to provide personalized game recommendations 
                                    based on our review database. Simply describe what you're looking for, and it will 
                                    suggest games that match your preferences.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
