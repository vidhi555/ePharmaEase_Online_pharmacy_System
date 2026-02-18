<?php

$features = [
    [
        "icon" => "fas fa-truck-medical",
        "title" => "Free Delivery",
        "desc"  => "Fast & reliable free delivery on selected medicines."
    ],
    [
        "icon" => "fas fa-shield-heart",
        "title" => "Secure Payment",
        "desc"  => "100% safe and encrypted payment methods for secure checkout."
    ],
    [
        "icon" => "fas fa-clock",
        "title" => "24/7 Support",
        "desc"  => "Our medical support team is available round the clock."
    ],
    [
        "icon" => "fas fa-tags",
        "title" => "Best Offers",
        "desc"  => "Exclusive healthcare deals and discounts every day."
    ]
];
?>
<section class="feature-section">
    <div class="feature-container">
        <?php foreach($features as $feature): ?>
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="<?= $feature['icon']; ?>"></i>
                </div>
                <h3><?= $feature['title']; ?></h3>
                <p><?= $feature['desc']; ?></p>
            </div>
        <?php endforeach; ?>
    </div>
</section>