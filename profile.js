document.addEventListener('DOMContentLoaded', function () {
    const editButton = document.querySelector('.edit-button');
    const saveButton = document.querySelector('.save-button');
    const inputs = document.querySelectorAll('#account-general input');

    
    function enableEditing() {
        inputs.forEach(input => input.removeAttribute('disabled'));
        editButton.style.display = 'none';
        saveButton.style.display = 'inline-block';
    }

    
    function disableEditing() {
        inputs.forEach(input => input.setAttribute('disabled', 'true'));
        editButton.style.display = 'inline-block';
        saveButton.style.display = 'none';
    }

    
    editButton.addEventListener('click', function () {
        enableEditing();
    });

    
    saveButton.addEventListener('click', function () {
        
        document.querySelector('form').submit(); 

       
        disableEditing();
    });
});



$(document).ready(function() {
    $.ajax({
        url: 'fetch_user_data.php', 
        type: 'GET',
        success: function(data) {
            const userData = JSON.parse(data);
            $('#displayName').text(userData.firstName + ' ' + userData.lastName);
            $('#displayEmail').text(userData.email);
            $('#displayCompany').text(userData.company);
            $('#profilePhoto').attr('src', userData.profile_photo || 'uploads/profile_pics/default.png');

            
            $('#displayBio').text('Bio: ' + userData.bio);
            $('#displayday').text('Birthday: ' + userData.birthday);
            $('#displayCountry').text('Country: ' + userData.country);
            $('#displayPhone').text('Phone: ' + userData.phone);
            $('#displayWebsite').text('Website: ' + userData.website);

            
            $('#firstName').val(userData.firstName);
            $('#lastName').val(userData.lastName);
            $('#email').val(userData.email);
            $('#company').val(userData.company);
            $('#bio').val(userData.bio);
            $('#birthday').val(userData.birthday);
            $('#country').val(userData.country);
            $('#phone').val(userData.phone);
            $('#website').val(userData.website);
        },
        error: function(error) {
            console.error('Error fetching user data:', error);
        }
    });

   
    $('#editButton').on('click', function(event) {
        event.preventDefault(); 
        $('#profileInfo').hide(); 
        $('#editProfileForm').show(); 
    });

    
    $('#saveButton').on('click', function(event) {
        event.preventDefault(); 
        const formData = new FormData();
        formData.append('firstName', $('#firstName').val());
        formData.append('lastName', $('#lastName').val());
        formData.append('email', $('#email').val());
        formData.append('company', $('#company').val());
        formData.append('bio', $('#bio').val());
        formData.append('birthday', $('#birthday').val());
        formData.append('country', $('#country').val());
        formData.append('phone', $('#phone').val());
        formData.append('website', $('#website').val());

        const profilePicInput = document.getElementById('uploadPhoto');
        if (profilePicInput.files.length > 0) {
            formData.append('profile_pic', profilePicInput.files[0]);
        }

        $.ajax({
            url: 'save_profile.php', 
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(data) {
                const response = JSON.parse(data);
                if (response.success) {
                    
                    $('#displayName').text(response.firstName + ' ' + response.lastName);
                    $('#displayEmail').text(response.email);
                    $('#displayCompany').text(response.company);
                    $('#profilePhoto').attr('src', response.profile_photo || 'uploads/profile_pics/default.png');
                    
                    
                    $('#displayBio').text('Bio: ' + response.bio);
                    $('#displayday').text('Birthday: ' + response.birthday);
                    $('#displayCountry').text('Country: ' + response.country);
                    $('#displayPhone').text('Phone: ' + response.phone);
                    $('#displayWebsite').text('Website: ' + response.website);

                    
                    $('#editProfileForm').hide();
                    $('#profileInfo').show();
                } else {
                    alert('Error: ' + response.error);
                }
            },
            error: function(error) {
                console.error('Error saving profile:', error);
            }
        });
    });
});
