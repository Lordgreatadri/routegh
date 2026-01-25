/* Profile form Alpine data for client-side validation
   This module registers an Alpine data component named 'profileForm'.
   Usage in Blade: x-data="profileForm()"
*/

document.addEventListener('alpine:init', () => {
    Alpine.data('profileForm', () => ({
        activeTab: 'profile',
        formData: {
            name: window.profileFormData?.name || '',
            email: window.profileFormData?.email || '',
            phone: window.profileFormData?.phone || ''
        },
        errors: {
            name: '',
            email: '',
            phone: ''
        },
        submitting: false,
        
        validate() {
            this.errors.name = '';
            this.errors.email = '';
            this.errors.phone = '';
            
            let isValid = true;
            
            if (!this.formData.name || this.formData.name.trim().length < 2) {
                this.errors.name = 'Name must be at least 2 characters';
                isValid = false;
            }
            
            if (this.formData.email && this.formData.email.trim()) {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(this.formData.email)) {
                    this.errors.email = 'Please enter a valid email address';
                    isValid = false;
                }
            }
            
            const phoneDigits = this.formData.phone.replace(/\D/g, '');
            if (!phoneDigits || phoneDigits.length < 7 || phoneDigits.length > 15) {
                this.errors.phone = 'Phone must be between 7-15 digits';
                isValid = false;
            }
            
            return isValid;
        },
        
        handleSubmit(event) {
            event.preventDefault();
            
            if (!this.validate()) {
                return;
            }
            
            this.submitting = true;
            event.target.closest('form').submit();
        }
    }));
});
