document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('deliveryForm');
    const timerDisplay = document.getElementById('timer');

    form.addEventListener('submit', function(event) {
      event.preventDefault();

      const productName = document.getElementById('productName').value;
      const productPrice = document.getElementById('productPrice').value;
      const deliveryDate = document.getElementById('deliveryDate').value;
      const deliveryTime = document.getElementById('deliveryTime').value;

      const deliveryDateTime = new Date(`${deliveryDate}T${deliveryTime}`);
      const now = new Date();

      const timeDiff = deliveryDateTime.getTime() - now.getTime();
      const hoursDiff = Math.floor(timeDiff / (1000 * 60 * 60));
      const minutesDiff = Math.floor((timeDiff % (1000 * 60 * 60)) / (1000 * 60));

      const timerText = `Delivery scheduled in ${hoursDiff} hours and ${minutesDiff} minutes.`;
      timerDisplay.textContent = timerText;

      const checkDeliveryTime = setInterval(function() {
        const currentTime = new Date();
        if (currentTime >= deliveryDateTime) {
          clearInterval(checkDeliveryTime);
          timerDisplay.textContent = "Delivery time has arrived!";
          Swal.fire({
            icon: 'info',
            title: 'Delivery Time!',
            text: 'Your delivery time has arrived!',
          });
        }
      }, 1500);
      Swal.fire({
        icon: 'success',
        title: 'Delivery Scheduled!',
        html: `Product Name: ${productName}<br>Product Price: ${productPrice}<br>Delivery scheduled for ${deliveryDate} at ${deliveryTime}`,
      });
    });
  });