document.addEventListener('DOMContentLoaded', function() {
    const categoryName = document.title.split(' - ')[0]; 
    const itemList = document.getElementById('item-list');

    itemList.innerHTML = '<p>Loading items...</p>';

    fetch(`fetch_coupons.php?category=${encodeURIComponent(categoryName)}`)
        .then(response => {
           
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(items => {
            
            itemList.innerHTML = '';

            
            if (items.length === 0) {
                itemList.innerHTML = '<p>No items found in this category.</p>';
                return;
            }

           
            items.forEach(item => {
                const itemDiv = document.createElement('div');
                itemDiv.classList.add('item');
                itemDiv.innerHTML = `
                    <img src="${item.image}" alt="${item.name}">
                    <h3>${item.name}</h3>
                    <p>${item.description}</p>
                    <p>Expires on: ${item.expirationDate || 'Not specified'}</p>
                    <p>Uploaded on: ${item.uploadDate}</p>
                    <a href="chat.php?item_id=${item.item_id}">Chat with Seller</a>
                `;

                itemList.appendChild(itemDiv);
            });
        })
        .catch(error => {
            console.error('Error loading items:', error);
            itemList.innerHTML = '<p>There was an error loading items. Please try again later.</p>';
        });
});
