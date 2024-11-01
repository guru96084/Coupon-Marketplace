document.addEventListener('DOMContentLoaded', function() {
    const couponsContainer = document.getElementById('coupons');

    // Sample coupons data - in real project, this would come from a database
    const coupons = [
        {
            title: '50% Off Pizza',
            description: 'Get 50% off on any large pizza at Pizza Place.',
            imageUrl: 'path/to/pizza-coupon.jpg',
            details: 'Valid until: 31/12/2024'
        },
        {
            title: 'Buy 1 Get 1 Free Coffee',
            description: 'Buy one coffee and get another free at Coffee Shop.',
            imageUrl: 'path/to/coffee-coupon.jpg',
            details: 'Valid until: 31/08/2024'
        },
        {
            title: '20% Off Sushi',
            description: 'Enjoy 20% off on all sushi orders at Sushi Bar.',
            imageUrl: 'path/to/sushi-coupon.jpg',
            details: 'Valid until: 30/09/2024'
        }
    ];

    coupons.forEach(coupon => {
        const couponElement = document.createElement('div');
        couponElement.classList.add('coupon');
        couponElement.innerHTML = `
            <img src="${coupon.imageUrl}" alt="${coupon.title}">
            <h3>${coupon.title}</h3>
            <p>${coupon.description}</p>
            <div class="details">
                <span>${coupon.details}</span>
                <a href="contact-seller.html">Contact Seller</a>
            </div>
        `;
        couponsContainer.appendChild(couponElement);
    });
});
