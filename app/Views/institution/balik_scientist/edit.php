<?= $this->extend('layouts/header-layout') ?>
<?= $this->section('content') ?>

<style>
    .modal-card-head,
    .modal-card-foot {
        background-color: #f0f0f0;
        border-bottom: 1px solid #ddd;
        border-top: 1px solid #ddd;
        padding: 0.75rem 1rem;
    }

    .modal-card-title {
        font-weight: 600;
        text-align: center;
        font-size: 1.25rem;
        color: #363636;
        margin: 0;
    }

    .modal-card-body {
        padding: 1.5rem;
        background-color: #fff;
    }

    .profile-image {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        overflow: hidden;
        display: inline-block;
        border: 2px solid #ddd;
        margin-bottom: 1rem;
    }

    .profile-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .title {
        color: #363636;
        margin-bottom: 0.75rem;
        font-weight: 500;
        font-size: 1.1rem;
    }

    .title.is-5 {
        font-weight: 700;
        /* Makes the title bold */
        font-size: 1.25rem;
        color: #363636;
        margin-bottom: 0.5rem;


        font-size: 1.25rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        color: #363636;
        border-bottom: 1px solid #eee;
        padding-bottom: 0.3rem;
    }

    .label {
        color: #555;
        font-weight: 500;
        margin-bottom: 0.25rem;
    }

    .input,
    .select select {
        border-radius: 4px;
        border: 1px solid #ccc;
        padding: 0.5rem;
        width: 100%;
        transition: border-color 0.2s;
        box-shadow: none;
    }

    .input:focus,
    .select select:focus {
        border-color: #3273dc;
        box-shadow: 0 0 0 2px rgba(50, 115, 220, 0.2);
    }

    .button {
        border-radius: 4px;
        padding: 0.5rem 1rem;
        font-size: 0.9rem;
        transition: background-color 0.2s, box-shadow 0.2s;
    }

    .button.is-success {
        background-color: #48c774;
        color: #fff;
    }

    .button.is-success:hover {
        background-color: #3dbb63;
    }

    .button.is-primary {
        background-color: #3273dc;
        color: #fff;
    }

    .button.is-primary:hover {
        background-color: #2759bd;
    }

    .button.is-success,
    .button.is-primary {
        box-shadow: none;
    }

    .field.has-addons .control .button {
        border-radius: 0 4px 4px 0;
        padding: 0.5rem 0.75rem;
    }

    .field.has-addons .control.is-expanded .input {
        border-radius: 4px 0 0 4px;
    }

    .has-text-right {
        text-align: right;
    }

    .columns.is-multiline .column {
        padding: 0.5rem;
    }

    .modal-background {
        background-color: rgba(0, 0, 0, 0.4);
    }

    .delete {
        color: #888;
        transition: color 0.2s;
    }

    .delete:hover {
        color: #ff3860;
    }

    .mt-4 {
        margin-top: 1.5rem;
    }

    #contact-info .field {
        margin-bottom: 1rem;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

<body>
    <!-- Main Modal for First Transaction -->
    <div class="modal is-active" id="main-modal">
        <div class="modal-background"></div>
        <div class="modal-card">
            <header class="modal-card-head">
                <p class="modal-card-title">Balik Scientist Information</p>
                <button class="delete" aria-label="close" onclick="exitModal()"></button>
            </header>
            <section class="modal-card-body">
                <div class="image-placeholder has-text-centered">
                    <figure class="profile-image">
                        <img src="/images/profile.png" alt="Profile Picture">
                    </figure>
                </div>
                <section class="modal-card-body">
                    <form id="stakeholder-form">
                        <h2 class="title is-5">Personal Information</h2>
                        <div class="columns is-multiline">
                            <div class="column is-one-quarter">
                                <label class="label">Prefix</label>
                                <div class="select is-fullwidth">
                                    <select>
                                        <option>Mr.</option>
                                        <option>Ms.</option>
                                        <option>Dr.</option>
                                        <option>Prof.</option>
                                    </select>
                                </div>
                            </div>
                            <div class="column is-one-quarter">
                                <label class="label">First Name</label>
                                <input class="input" type="text" placeholder="First Name" required>
                            </div>
                            <div class="column is-one-quarter">
                                <label class="label">Middle Initial</label>
                                <input class="input" type="text" placeholder="M.I.">
                            </div>
                            <div class="column is-one-quarter">
                                <label class="label">Last Name</label>
                                <input class="input" type="text" placeholder="Last Name" required>
                            </div>
                            <div class="column is-one-quarter">
                                <label class="label">Extension</label>
                                <input class="input" type="text" placeholder="Jr., Sr., III">
                            </div>
                            <div class="column is-one-quarter">
                                <label class="label">Salutation</label>
                                <input class="input" type="text" placeholder=" Honorable">
                            </div>
                            <div class="column is-one-quarter">
                                <label class="label">Honorifics</label>
                                <input class="input" type="text" placeholder="Ph.D., MD">
                            </div>
                        </div>

                        <h2 class="title is-5">Designation</h2>
                        <div class="field">
                            <label class="label">Position</label>
                            <input class="input" type="text" placeholder="Position">
                        </div>
                        <div class="field">
                            <label class="label">Agency/Organization</label>
                            <input class="input" type="text" placeholder="Agency or Organization">
                        </div>
                        <h2 class="title is-5">Contact Information</h2>
                        <div class="columns is-multiline" id="contact-info">
                            <div class="column is-half">
                                <label class="label">Tel. No.</label>
                                <input class="input" type="tel" placeholder="Tel. No.">
                            </div>
                            <div class="column is-half">
                                <label class="label">Fax No.</label>
                                <input class="input" type="tel" placeholder="Fax No.">
                            </div>
                            <div class="column is-half">
                                <label class="label">Cell No.</label>
                                <div class="field has-addons">
                                    <p class="control is-expanded">
                                        <input class="input" type="tel" placeholder="Cell No.">
                                    </p>
                                    <p class="control">
                                        <button type="button" class="button is-success" onclick="addField('cell')">
                                            <span class="icon is-small">
                                                <i class="fas fa-plus"></i>
                                            </span>
                                        </button>
                                    </p>
                                </div>
                            </div>

                            <div class="column is-half">
                                <label class="label">Email</label>
                                <div class="field has-addons">
                                    <p class="control is-expanded">
                                        <input class="input" type="email" placeholder="Email">
                                    </p>
                                    <p class="control">
                                        <button type="button" class="button is-success" onclick="addField('email')">
                                            <span class="icon is-small">
                                                <i class="fas fa-plus"></i>
                                            </span>
                                        </button>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <h2 class="title is-5">Complete Address</h2>
                        <div class="columns is-multiline">
                            <div class="column is-half">
                                <label class="label">Street</label>
                                <input class="input" type="text" placeholder="Street">
                            </div>
                            <div class="column is-half">
                                <label class="label">Barangay</label>
                                <input class="input" type="text" placeholder="Barangay">
                            </div>
                            <div class="column is-half">
                                <label class="label">Municipality</label>
                                <input class="input" type="text" placeholder="Municipality" required>
                            </div>
                            <div class="column is-half">
                                <label class="label">Province</label>
                                <input class="input" type="text" placeholder="Province" required>
                            </div>
                            <div class="column is-half">
                                <label class="label">Country</label>
                                <input class="input" type="text" placeholder="Country">
                            </div>
                        </div>

                        <h2 class="title is-5">Research Projects</h2>
                        <div class="field">
                            <textarea class="textarea" placeholder="List of Research Projects"></textarea>
                        </div>


                        <section class="modal-card-body has-text-right">
                            <button class="button is-success" onclick="confirmTransaction()">Confirm</button>
                        </section>
                    </form>
                </section>
        </div>
    </div>


    <!-- Confirmation Modal -->
    <div class="modal" id="warning-modal">
        <div class="modal-background"></div>
        <div class="modal-card">
            <header class="modal-card-head">
                <p class="modal-card-title">Confirmation</p>
                <button class="delete" aria-label="close" onclick="closeConfirmationModal()"></button>
            </header>
            <section class="modal-card-body has-text-centered">
                <p>Transaction has been saved!</p>
            </section>
            <footer class="modal-card-foot is-justify-content-center">
                <button class="button is-primary" onclick="closeConfirmationModal()">OK</button>
            </footer>
        </div>
    </div>


    <script>
        // Function to add fields dynamically

        function addField(type) {
            const contactInfo = document.getElementById("contact-info");
            const column = document.createElement("div");
            column.className = "column is-half";

            let label, placeholder, inputType;
            if (type === "cell") {
                label = "Cell No.";
                placeholder = "Additional Cell No.";
                inputType = "tel";
            } else if (type === "email") {
                label = "Email";
                placeholder = "Additional Email";
                inputType = "email";
            }

            column.innerHTML = `
                <label class="label mr-2">${label}</label>
                <div class="field has-addons">
                    <p class="control is-expanded">
                        <input class="input" type="${inputType}" placeholder="${placeholder}">
                    </p>
                    <p class="control">
                        <button type="button" class="button is-danger" onclick="removeField(this)">
                            <span class="icon is-small">
                                <i class="fas fa-minus"></i>
                            </span>
                        </button>
                    </p>
                </div>
            `;
            contactInfo.appendChild(column);
        }

        // Function to remove a field
        function removeField(button) {
            button.closest('.column').remove();
        }

        // Function to show the confirmation modal
        // Function to add fields dynamically
        function addField(type) {
            const contactInfo = document.getElementById("contact-info");
            const column = document.createElement("div");
            column.className = "column is-half";

            let label, placeholder, inputType;
            if (type === "cell") {
                label = "Cell No.";
                placeholder = "Additional Cell No.";
                inputType = "tel";
            } else if (type === "email") {
                label = "Email";
                placeholder = "Additional Email";
                inputType = "email";
            }

            column.innerHTML = `
        <label class="label mr-2">${label}</label>
        <div class="field has-addons">
            <p class="control is-expanded">
                <input class="input" type="${inputType}" placeholder="${placeholder}">
            </p>
            <p class="control">
                <button type="button" class="button is-danger" onclick="removeField(this)">
                    <span class="icon is-small">
                        <i class="fas fa-minus"></i>
                    </span>
                </button>
            </p>
        </div>
    `;
            contactInfo.appendChild(column);
        }

        // Function to remove a field
        function removeField(button) {
            button.closest('.column').remove();
        }

        // Function to show the confirmation modal
        // Function to show the confirmation modal
        function confirmTransaction() {
            const mainModal = document.getElementById('main-modal');
            const warningModal = document.getElementById('warning-modal');

            // Hide main modal and show confirmation modal
            mainModal.classList.remove('is-active');
            warningModal.classList.add('is-active');
        }

        // Function to close the confirmation modal when "OK" button is clicked
        function closeConfirmationModal() {
            const warningModal = document.getElementById('warning-modal');
            warningModal.classList.remove('is-active');
        }


        // Function to close the main modal
        function exitModal() {
            const mainModal = document.getElementById('main-modal');
            if (mainModal) {
                mainModal.classList.remove('is-active');
            }

            // Redirect to the specified page after closing the modal
            window.location.href = "<?= base_url('institution/balik_scientist/index') ?>";
        }


    </script>

</body>

</html>

<?= $this->endSection() ?>