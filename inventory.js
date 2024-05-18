function resetForm() {
  document.getElementById("add-item-form").reset();
}

// Reset the form fields to their initial values
function resetEditForm() {
  document.getElementById("updatecode").value = "";
  document.getElementById("updatename").value = "";
  document.getElementById("updateexp").value = "";
  document.getElementById("updateqty").value = "";
  document.getElementById("updateprice").value = "";
}

$("#add-item-form").submit(function (e) {
  e.preventDefault();

  $.ajax({
    type: "POST",
    url: $(this).attr("action"),
    data: $(this).serialize(),
    dataType: "json",
    success: function (response) {
      if (response.success) {
        // Show success message
        Swal.fire({
          title: "Success!",
          text: response.message,
          icon: "success",
        }).then(() => {
          window.location.href = "inventory.php";
        });
      } else {
        // Show error message
        Swal.fire({
          title: "Error!",
          text: response.message,
          icon: "error",
          confirmButtonText: "OK",
        });
      }
    },
    error: function (xhr, status, error) {
      // Show error message for AJAX request failure
      Swal.fire({
        title: "Error!",
        text: "Failed to add item. Please try again.",
        icon: "error",
        confirmButtonText: "OK",
      });
    },
  });
});

function confirmDelete(id) {
  Swal.fire({
    title: "Are you sure?",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, delete it!",
  }).then((result) => {
    if (result.isConfirmed) {
      deleteItem(id);
    }
  });
}

function deleteItem(id) {
  // Show loading indicator
  Swal.fire({
    title: "Deleting...",
    text: "Please wait",
    allowOutsideClick: false,
    onBeforeOpen: () => {
      Swal.showLoading();
    },
  });

  $.ajax({
    type: "POST",
    url: "delete.php",
    data: { id: id },
    success: function (response) {
      Swal.fire({
        title: "Deleted!",
        text: "Your item has been deleted.",
        icon: "success",
      }).then(() => {
        window.location.href = "inventory.php";
      });
    },
    error: function (xhr, status, error) {
      Swal.fire({
        title: "Error!",
        text: "An error occurred while deleting the item.",
        icon: "error",
      });
    },
  });
}


document.getElementById('btnUpdate').addEventListener('click', function() {
    const form = document.getElementById('editForm');
    const formData = new FormData(form);

    fetch('update.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Updated!',
                text: data.message,
            }).then(() => {
                location.reload();  // Reload the page to reflect changes
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: data.message,
            });
        }
    })
    .catch(error => {
        console.error('Error updating item:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: 'There was an error updating the item.',
        });
    });
});