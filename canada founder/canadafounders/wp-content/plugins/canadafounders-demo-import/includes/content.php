<?php
// This file defines the structure and content for the demo pages being imported, including sample text and layout.

function canadafounders_get_demo_content() {
    return [
        'home' => [
            'title' => 'Where Canada Builds Business',
            'content' => '
                <h1>Where Canada Builds Business</h1>
                <p>Join a vibrant community of founders, entrepreneurs, and business professionals across Canada.</p>
                <a href="#" class="cta">Join the Community</a>
                <a href="#" class="cta secondary">Explore the Network</a>
                <h2>What Are You Looking to Do?</h2>
                <div class="navigation-cards">
                    <div class="card">Connect</div>
                    <div class="card">Learn</div>
                    <div class="card">Access</div>
                    <div class="card">Grow</div>
                    <div class="card">Give Back</div>
                </div>
                <h2>Featured Members/Businesses</h2>
                <div class="featured-members"></div>
                <h2>Upcoming Events</h2>
                <div class="upcoming-events"></div>
                <h2>Funding and Resources</h2>
                <div class="funding-resources-preview"></div>
                <h2>Founder Stories</h2>
                <div class="founder-stories-preview"></div>
                <h2>Partners</h2>
                <div class="partners-section"></div>
                <p>Join us and get involved!</p>
            ',
        ],
        'about' => [
            'title' => 'About CanadaFounders',
            'content' => '
                <h1>Who We Are</h1>
                <p>CanadaFounders is dedicated to connecting founders and business owners across Canada.</p>
                <h2>Mission and Purpose</h2>
                <p>Our mission is to foster collaboration and support among entrepreneurs.</p>
                <h2>Community Value</h2>
                <p>We serve a diverse community of founders, entrepreneurs, and small businesses.</p>
                <h2>Invitation to Participate</h2>
                <p>Join us in building a stronger business community.</p>
            ',
        ],
        'community' => [
            'title' => 'Community',
            'content' => '
                <h1>What Our Community Offers</h1>
                <p>We provide resources, networking opportunities, and support for all participants.</p>
                <h2>Ways to Connect</h2>
                <p>Learn, share experiences, and discover new opportunities with us.</p>
                <a href="#" class="cta">Join Now</a>
            ',
        ],
        'members_directory' => [
            'title' => 'Members Directory',
            'content' => '
                <h1>Our Members</h1>
                <p>Meet our community members.</p>
                <div class="members-list"></div>
                <p>No members found. Please check back later.</p>
            ',
        ],
        'events' => [
            'title' => 'Events',
            'content' => '
                <h1>Upcoming Events</h1>
                <p>Join us for our upcoming events.</p>
                <div class="events-list"></div>
                <p>No events scheduled at this time.</p>
            ',
        ],
        'partners' => [
            'title' => 'Partners',
            'content' => '
                <h1>Our Partners</h1>
                <p>We collaborate with various organizations to support our community.</p>
                <div class="partners-list"></div>
                <p>No partners available at this time.</p>
            ',
        ],
        'funding_resources' => [
            'title' => 'Funding & Resources',
            'content' => '
                <h1>Finding Business Resources in Canada</h1>
                <p>Explore funding information and business support resources.</p>
                <h2>Funding Information</h2>
                <p>Details about available funding options.</p>
                <h2>Business Support</h2>
                <p>Resources for business support and learning.</p>
            ',
        ],
        'founder_stories' => [
            'title' => 'Founder Stories',
            'content' => '
                <h1>Inspiring Stories from Founders</h1>
                <p>Read about the journeys of successful founders.</p>
                <div class="stories-list"></div>
                <p>No stories available at this time.</p>
            ',
        ],
        'join_community' => [
            'title' => 'Join the Community',
            'content' => '
                <h1>Join Us</h1>
                <p>Become a part of our thriving community.</p>
                <a href="#" class="cta">Sign Up Now</a>
            ',
        ],
        'contact' => [
            'title' => 'Contact Us',
            'content' => '
                <h1>Get in Touch</h1>
                <p>We would love to hear from you!</p>
                <div class="contact-form"></div>
            ',
        ],
    ];
}
?>