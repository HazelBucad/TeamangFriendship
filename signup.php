<?php session_start() ?>
<div class="container-fluid">
	<form action="" id="signup-frm">
		<div class="form-group">
			<label for="" class="control-label">Firstname</label>
			<input type="text" name="first_name" required="" class="form-control">
		</div>
		<div class="form-group">
			<label for="" class="control-label">Lastname</label>
			<input type="text" name="last_name" required="" class="form-control">
		</div>
        <div class="form-group">
            <label for="" class="control-label">Contact</label>
            <input type="text" name="mobile" required="" class="form-control" oninput="validateContactNumber(this)">
            <span id="contactError" style="color: red;"></span>
        </div>
		<div class="form-group">
			<label for="" class="control-label">Address</label>
			<textarea cols="30" rows="3" name="address" required="" class="form-control"></textarea>
		</div>
		<div class="form-group">
			<label for="" class="control-label">Email</label>
			<input type="email" name="email" required="" class="form-control">
		</div>
		<div class="form-group">
        <label for="password" class="control-label">Password</label>
        <input type="password" name="password" id="password" required class="form-control">
        <i class="password-toggle fas fa-eye-slash" onclick="togglePasswordVisibility('password')"></i>
    </div>
    <div class="form-group">
        <label for="confirmPassword" class="control-label">Confirm Password</label>
        <input type="password" name="confirmPassword" id="confirmPassword" required class="form-control">
        <i class="password-toggle fas fa-eye-slash" onclick="togglePasswordVisibility('confirmPassword')"></i>
        <span id="passwordError" style="color: red;"></span>
    </div>
		<button class="button btn btn-info btn-sm">Create</button>
	</form>
</div>

<style>
	#uni_modal .modal-footer{
		display:none;
	}
        .form-group {
            position: relative;
        }

        .password-toggle {
            position: absolute;
            top: 75%;
            right: 10px;
            transform: translateY(-50%);
            cursor: pointer;
        }
</style>
<script>
	$('#signup-frm').submit(function (e) {
    e.preventDefault();

    // Disable the submit button and show a loading message
    $('#signup-frm button[type="submit"]').attr('disabled', true).html('Saving...');

    // Remove any existing error messages
    if ($(this).find('.alert-danger').length > 0)
        $(this).find('.alert-danger').remove();

    // Serialize the form data and send an AJAX request
    $.ajax({
        url: 'admin/ajax.php?action=signup',
        method: 'POST',
        data: $(this).serialize(),
        error: function (err) {
            console.log(err);
            // Enable the submit button and show an error message
            $('#signup-frm button[type="submit"]').removeAttr('disabled').html('Create');
        },
        success: function (resp) {
            if (resp == 1) {
                location.href = '<?php echo isset($_GET['redirect']) ? $_GET['redirect'] : 'index.php?page=home' ?>';
            } else {
                // Show an error message for email duplication
                $('#signup-frm').prepend('<div class="alert alert-danger">Email already exists.</div>');
                // Enable the submit button
                $('#signup-frm button[type="submit"]').removeAttr('disabled').html('Create');
            }
        }
    });
});

// Password visibility toggle
$('.password-toggle').click(function () {
    const passwordField = $('#password');
    const confirmPasswordField = $('#confirmPassword');

    // Toggle password visibility for both fields
    togglePasswordVisibility(passwordField);
    togglePasswordVisibility(confirmPasswordField);
});

function togglePasswordVisibility(field) {
    const fieldType = field.attr('type');
    if (fieldType === 'password') {
        field.attr('type', 'text');
    } else {
        field.attr('type', 'password');
    }
}
    function togglePasswordVisibility(fieldId) {
        const passwordField = document.getElementById(fieldId);
        const passwordToggle = document.querySelector(`#${fieldId} + .password-toggle`);

        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            passwordToggle.classList.remove('fa-eye-slash');
            passwordToggle.classList.add('fa-eye');
        } else {
            passwordField.type = 'password';
            passwordToggle.classList.remove('fa-eye');
            passwordToggle.classList.add('fa-eye-slash');
        }
    }

    document.getElementById('signup-frm').addEventListener('submit', function (e) {
        // Perform client-side validation before submitting the form
        var password = document.getElementById('password').value;
        var confirmPassword = document.getElementById('confirmPassword').value;
        var passwordError = document.getElementById('passwordError');

        if (password !== confirmPassword) {
            e.preventDefault();
            passwordError.textContent = 'Passwords do not match';
            $('#signup-frm button[type="submit"]').removeAttr('disabled').html('Create');
        } else {
            passwordError.textContent = ''; // Clear error message if passwords match
        }
    });
    $('#signup-frm').submit(function (e) {
        // ... Other form submission code ...

        // Call the validation function for the contact number
        validateContactNumber(document.getElementsByName('mobile')[0]);

        // Prevent form submission if there are validation errors
        if ($('#contactError').text() !== '') {
            e.preventDefault();
        }
    });

    function validateContactNumber(inputField) {
        const contactNumber = inputField.value.trim(); // Trim leading/trailing spaces
        const contactError = document.getElementById('contactError');

        if (contactNumber.length !== 11 || isNaN(contactNumber)) {
            contactError.textContent = 'Contact number must be 11 digits';
            $('#signup-frm button[type="submit"]').attr('disabled', true);
        } else {
            contactError.textContent = '';
            $('#signup-frm button[type="submit"]').removeAttr('disabled');
        }
    }
</script>