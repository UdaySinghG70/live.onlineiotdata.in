<?php
session_start();

if(isset($_SESSION['admin_name'])==false){
    echo "Invalid Login";
    header('Location: login.php');
    return;
}

$admin_name=$_SESSION['admin_name'];

include_once '../model/adminlogin_dao.php';
$adao=new AdminLoginDao();

$adminDetails=$adao->getAdminByUserName($admin_name);
if($adminDetails==null){
    echo "Invalid Login";
    header('Location: login.php?msg=error&admin_name='.$admin_name);
    return;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Dashboard - Cloud Data Monitoring</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="../images/icons/favicon.png"/>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="css/admin-style.css">
    <style>
        /* Dashboard specific styles */
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .dashboard-grid-2 {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stats-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }

        .stats-card:hover {
            transform: translateY(-2px);
        }

        .stats-header {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1rem;
        }

        .stats-icon {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            background: #e3f2fd;
            color: #0067ac;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .stats-title {
            font-size: 0.875rem;
            color: #64748b;
            font-weight: 500;
        }

        .stats-value {
            font-size: 1.5rem;
            font-weight: 600;
            color: #1e293b;
        }

        .welcome-message {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        .welcome-message h2 {
            font-size: 1.5rem;
            color: #1e293b;
            margin-bottom: 1rem;
        }

        .welcome-message p {
            color: #64748b;
            line-height: 1.6;
        }

        /* Modal styles for Configure Preset Parameters popup */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 0;
            border-radius: 12px;
            width: 80%;
            max-width: 800px;
            max-height: 80vh;
            overflow-y: auto;
            box-shadow: 0 4px 20px rgba(0,0,0,0.3);
        }

        .modal-header {
            background: #0067ac;
            color: white;
            padding: 1.5rem;
            border-radius: 12px 12px 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-header h2 {
            margin: 0;
            font-size: 1.5rem;
        }

        .close {
            color: white;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            line-height: 1;
        }

        .close:hover,
        .close:focus {
            color: #e3f2fd;
        }

        .modal-body {
            padding: 2rem;
        }

        .preset-form {
            display: grid;
            gap: 1.5rem;
        }

        .parameters-section {
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 1.5rem;
            background: #f9fafb;
        }

        .parameters-section h3 {
            margin: 0 0 1rem 0;
            color: #374151;
            font-size: 1.1rem;
        }

        .parameter-row {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr auto;
            gap: 1rem;
            align-items: end;
            padding: 1rem;
            background: white;
            border-radius: 6px;
            margin-bottom: 1rem;
            border: 1px solid #e5e7eb;
        }

        .btn-danger {
            background: #dc2626;
            color: white;
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
        }

        .btn-danger:hover {
            background: #b91c1c;
        }

        #addParameter {
            margin-top: 1rem;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .form-group label {
            font-weight: 500;
            color: #374151;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            padding: 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 0.875rem;
        }

        .form-group textarea {
            resize: vertical;
            min-height: 100px;
        }

        .form-actions {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e5e7eb;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 6px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-primary {
            background: #0067ac;
            color: white;
        }

        .btn-primary:hover {
            background: #005a8f;
        }

        .btn-secondary {
            background: #6b7280;
            color: white;
        }

        .btn-secondary:hover {
            background: #4b5563;
        }

        /* Make stats cards clickable */
        .stats-card.clickable {
            cursor: pointer;
        }

        .stats-card.clickable:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        @media (max-width: 768px) {
            .dashboard-grid,
            .dashboard-grid-2 {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .stats-card {
                padding: 1rem;
            }

            .welcome-message {
                padding: 1.5rem;
            }

            .modal-content {
                width: 95%;
                margin: 10% auto;
            }

            .modal-body {
                padding: 1.5rem;
            }

            .form-actions {
                flex-direction: column;
            }

            .parameter-row {
                grid-template-columns: 1fr;
                gap: 0.75rem;
            }

            .parameter-row .btn-danger {
                margin-top: 0.5rem !important;
            }
        }

        .enhanced-modal {
            box-shadow: 0 8px 32px rgba(0,0,0,0.18), 0 1.5px 6px rgba(0,0,0,0.08);
            border-radius: 18px;
            background: #fafdff;
            padding: 0;
            overflow: visible;
        }
        .enhanced-modal-header {
            background: linear-gradient(90deg, #0067ac 60%, #0088e0 100%);
            color: #fff;
            border-radius: 18px 18px 0 0;
            padding: 1.5rem 2rem 1.2rem 2rem;
            position: sticky;
            top: 0;
            z-index: 2;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .enhanced-modal-header h2 {
            font-size: 1.4rem;
            font-weight: 600;
            margin: 0;
        }
        .enhanced-modal-header .close {
            font-size: 2rem;
            color: #fff;
            opacity: 0.85;
            transition: opacity 0.2s;
        }
        .enhanced-modal-header .close:hover {
            opacity: 1;
            color: #ffe066;
        }
        .enhanced-preset-form {
            padding: 2rem 2rem 1.5rem 2rem;
            background: transparent;
        }
        .modal-section {
            margin-bottom: 1.5rem;
        }
        .enhanced-parameters-section {
            background: #f3f7fa;
            border-radius: 10px;
            border: 1px solid #e3e8ee;
            padding: 1.2rem 1rem 1rem 1rem;
            box-shadow: 0 1px 4px rgba(0,0,0,0.04);
        }
        .parameters-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1rem;
        }
        .parameters-header h3 {
            margin: 0;
            font-size: 1.08rem;
            color: #0067ac;
            font-weight: 600;
        }
        .enhanced-add-btn {
            background: #e3f6ff;
            color: #0067ac;
            border: 1px solid #b6e0fe;
            font-weight: 600;
            border-radius: 6px;
            padding: 0.5rem 1.2rem;
            font-size: 1rem;
            transition: background 0.2s, color 0.2s;
        }
        .enhanced-add-btn:hover {
            background: #b6e0fe;
            color: #005a8f;
        }
        .scrollable-params {
            max-height: 260px;
            overflow-y: auto;
            margin-bottom: 0.5rem;
        }
        .enhanced-parameter-row {
            background: #fff;
            border-radius: 7px;
            box-shadow: 0 1px 4px rgba(0,0,0,0.03);
            border: 1px solid #e3e8ee;
            margin-bottom: 1rem;
            padding: 1rem 0.8rem 0.8rem 0.8rem;
            display: grid;
            grid-template-columns: 2fr 1.2fr 1.2fr auto;
            gap: 1rem;
            align-items: end;
        }
        .enhanced-remove-btn {
            background: #fff0f0;
            color: #e03131;
            border: 1px solid #ffd6d6;
            border-radius: 6px;
            font-size: 1.2rem;
            padding: 0.4rem 0.9rem;
            margin-left: 0.2rem;
            transition: background 0.2s, color 0.2s;
        }
        .enhanced-remove-btn:hover {
            background: #ffe3e3;
            color: #b91c1c;
        }
        .enhanced-form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 1.2rem;
            border-top: 1px solid #e3e8ee;
            padding-top: 1.2rem;
            margin-top: 1.5rem;
            background: transparent;
        }
        .enhanced-form-actions .btn {
            min-width: 120px;
            font-size: 1rem;
            font-weight: 500;
        }
        .enhanced-form-actions .btn-primary {
            background: linear-gradient(90deg, #0067ac 60%, #0088e0 100%);
            border: none;
        }
        .enhanced-form-actions .btn-primary:hover {
            background: linear-gradient(90deg, #005a8f 60%, #0077c7 100%);
        }
        .enhanced-form-actions .btn-secondary {
            background: #e3e8ee;
            color: #374151;
            border: 1px solid #cbd5e1;
        }
        .enhanced-form-actions .btn-secondary:hover {
            background: #cbd5e1;
            color: #1e293b;
        }
        .enhanced-parameter-row .form-group label {
            font-size: 0.97rem;
            color: #374151;
            font-weight: 500;
        }
        .enhanced-parameter-row .form-group input,
        .enhanced-parameter-row .form-group select {
            font-size: 0.97rem;
            padding: 0.6rem 0.7rem;
            border-radius: 5px;
            border: 1.5px solid #d1d5db;
            background: #fafdff;
            transition: border 0.2s;
        }
        .enhanced-parameter-row .form-group input:focus,
        .enhanced-parameter-row .form-group select:focus {
            border: 1.5px solid #0067ac;
            background: #e3f6ff;
        }
        @media (max-width: 768px) {
            .enhanced-modal {
                width: 98vw !important;
                min-width: 0;
            }
            .enhanced-modal-header,
            .enhanced-preset-form {
                padding-left: 1rem;
                padding-right: 1rem;
            }
            .enhanced-parameter-row {
                grid-template-columns: 1fr;
                gap: 0.7rem;
                padding: 0.7rem 0.5rem 0.5rem 0.5rem;
            }
            .enhanced-form-actions {
                flex-direction: column;
                gap: 0.7rem;
            }
        }
    </style>
</head>
<body>
    <?php include_once 'admin_header.php';?>

    <main class="dashboard">
        <div class="welcome-message">
            <h2>Welcome, <?php echo htmlspecialchars($adminDetails->admin_name); ?></h2>
            <p>Access and manage your cloud monitoring system from this admin dashboard. Use the navigation above to access different sections of the admin panel.</p>
        </div>

        <div class="dashboard-grid">
            <div class="stats-card">
                <div class="stats-header">
                    <div class="stats-icon">
                        <span class="material-icons">devices</span>
                    </div>
                    <div class="stats-title">Total Devices</div>
                </div>
                <div class="stats-value">--</div>
            </div>

            <div class="stats-card">
                <div class="stats-header">
                    <div class="stats-icon">
                        <span class="material-icons">people</span>
                    </div>
                    <div class="stats-title">Active Users</div>
                </div>
                <div class="stats-value">--</div>
            </div>

            <div class="stats-card">
                <div class="stats-header">
                    <div class="stats-icon">
                        <span class="material-icons">sync</span>
                    </div>
                    <div class="stats-title">Last Backup</div>
                </div>
                <div class="stats-value">--</div>
                <div class="backup-details" style="margin-top: 8px; font-size: 0.875rem; color: #64748b;">
                    <div class="backup-type">Type: --</div>
                    <div class="backup-tables">Tables: --</div>
                </div>
            </div>
        </div>

        <div class="dashboard-grid-2">
            <div class="stats-card clickable" id="presetParamsCard">
                <div class="stats-header">
                    <div class="stats-icon">
                        <span class="material-icons">settings</span>
                    </div>
                    <div class="stats-title">Configure Preset Parameters</div>
                </div>
                <div class="stats-value">Click to Configure</div>
            </div>
        </div>
    </main>

    <!-- Configure Preset Parameters Modal -->
    <div id="presetParamsModal" class="modal">
        <div class="modal-content enhanced-modal">
            <div class="modal-header enhanced-modal-header">
                <h2>Configure Preset Parameters</h2>
                <span class="close" title="Close">&times;</span>
            </div>
            <form class="preset-form enhanced-preset-form" id="presetParamsForm">
                <div class="modal-section">
                    <div class="form-group">
                        <label for="presetName"><span style="color:#e03131">*</span> Preset Name</label>
                        <input type="text" id="presetName" name="presetName" placeholder="Enter preset name" required>
                    </div>
                </div>
                <div class="modal-section">
                    <div class="parameters-section enhanced-parameters-section">
                        <div class="parameters-header">
                            <h3>Parameters</h3>
                            <button type="button" class="btn btn-secondary enhanced-add-btn" id="addParameter" title="Add Parameter"><span style="font-size:1.2em;vertical-align:middle;">＋</span> Add</button>
                        </div>
                        <div id="parametersList" class="scrollable-params">
                            <div class="parameter-row enhanced-parameter-row">
                                <div class="form-group">
                                    <label><span style="color:#e03131">*</span> Parameter Name</label>
                                    <input type="text" name="paramName[]" placeholder="e.g., Temperature" required>
                                </div>
                                <div class="form-group">
                                    <label><span style="color:#e03131">*</span> Type</label>
                                    <select name="paramType[]" class="param-type-select" required>
                                        <option value="">Select type</option>
                                        <option value="Date Time">Date Time</option>
                                        <option value="Alpha">Alpha</option>
                                        <option value="Numeric">Numeric</option>
                                        <option value="Float">Float</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Unit</label>
                                    <input type="text" name="paramUnit[]" class="param-unit-input" placeholder="e.g., °C">
                                </div>
                                <button type="button" class="btn btn-danger enhanced-remove-btn remove-param" title="Remove Parameter" style="margin-top: 1.5rem;">✖</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-actions enhanced-form-actions">
                    <button type="button" class="btn btn-secondary" id="cancelPreset">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Preset</button>
                </div>
            </form>
        </div>
    </div>

    <script src="../vendor/jquery/jquery-3.2.1.min.js"></script>
    <script src="../js/jquery-ui.js"></script>
    <script>
        // Show/hide loader
        function toggleLoader(show) {
            const loader = document.querySelector('.loader');
            if (show) {
                loader.classList.add('active');
            } else {
                loader.classList.remove('active');
            }
        }

        // Function to update device count
        function updateDeviceCount() {
            return $.ajax({
                url: 'get_device_count.php',
                method: 'GET',
                success: function(response) {
                    if(response.count !== undefined) {
                        $('.stats-card:eq(0) .stats-value').text(response.count);
                    }
                },
                error: function() {
                    $('.stats-card:eq(0) .stats-value').text('Error');
                }
            });
        }

        // Function to update user count
        function updateUserCount() {
            return $.ajax({
                url: 'get_user_count.php',
                method: 'GET',
                success: function(response) {
                    if(response.count !== undefined) {
                        $('.stats-card:eq(1) .stats-value').text(response.count);
                    }
                },
                error: function() {
                    $('.stats-card:eq(1) .stats-value').text('Error');
                }
            });
        }

        // Function to update last backup details
        function updateLastBackup() {
            return $.ajax({
                url: 'get_last_backup.php',
                method: 'GET',
                success: function(response) {
                    const backupCard = $('.stats-card:eq(2)');
                    if(response.success) {
                        backupCard.find('.stats-value').text(response.date);
                        backupCard.find('.backup-type').text('Type: ' + response.schedule);
                        backupCard.find('.backup-tables').text('Tables: ' + response.tables);
                    } else {
                        backupCard.find('.stats-value').text('No Backup');
                        backupCard.find('.backup-type').text('Type: --');
                        backupCard.find('.backup-tables').text('Tables: --');
                    }
                },
                error: function() {
                    const backupCard = $('.stats-card:eq(2)');
                    backupCard.find('.stats-value').text('Error');
                    backupCard.find('.backup-type').text('Type: --');
                    backupCard.find('.backup-tables').text('Tables: --');
                }
            });
        }

        // Example of how to fetch and update stats
        function updateStats() {
            toggleLoader(true);
            
            // Update all stats
            Promise.all([
                updateDeviceCount(),
                updateUserCount(),
                updateLastBackup()
            ]).finally(() => {
                toggleLoader(false);
            });
        }

        // Update stats when page loads
        document.addEventListener('DOMContentLoaded', updateStats);

        // Update stats every 5 minutes
        setInterval(updateStats, 300000);

        // Modal functionality for Configure Preset Parameters
        const modal = document.getElementById('presetParamsModal');
        const presetCard = document.getElementById('presetParamsCard');
        const closeBtn = document.querySelector('.close');
        const cancelBtn = document.getElementById('cancelPreset');
        const presetForm = document.getElementById('presetParamsForm');
        const addParamBtn = document.getElementById('addParameter');
        const parametersList = document.getElementById('parametersList');

        // Open modal when card is clicked
        presetCard.addEventListener('click', function() {
            modal.style.display = 'block';
            document.body.style.overflow = 'hidden'; // Prevent background scrolling
        });

        // Close modal when X is clicked
        closeBtn.addEventListener('click', function() {
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
            presetForm.reset();
        });

        // Close modal when Cancel button is clicked
        cancelBtn.addEventListener('click', function() {
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
            presetForm.reset();
        });

        // Close modal when clicking outside of it
        window.addEventListener('click', function(event) {
            if (event.target === modal) {
                modal.style.display = 'none';
                document.body.style.overflow = 'auto';
                presetForm.reset();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape' && modal.style.display === 'block') {
                modal.style.display = 'none';
                document.body.style.overflow = 'auto';
                presetForm.reset();
            }
        });

        // Utility: handle auto-fill of unit for Date Time
        function handleTypeUnitAutofill(selectElem) {
            const unitInput = selectElem.closest('.parameter-row').querySelector('.param-unit-input');
            if (selectElem.value === 'Date Time') {
                unitInput.value = 'DD-MM-YYYY hh:mm:ss';
            } else if (unitInput.value === 'DD-MM-YYYY hh:mm:ss') {
                unitInput.value = '';
            }
        }

        // Attach change event to all type selects (on page load and when adding rows)
        function attachTypeUnitListeners() {
            document.querySelectorAll('.param-type-select').forEach(function(selectElem) {
                selectElem.removeEventListener('change', selectElem._typeUnitListener);
                selectElem._typeUnitListener = function() { handleTypeUnitAutofill(selectElem); };
                selectElem.addEventListener('change', selectElem._typeUnitListener);
            });
        }
        // Initial attach
        attachTypeUnitListeners();

        // Add new parameter row
        addParamBtn.addEventListener('click', function() {
            const newRow = document.createElement('div');
            newRow.className = 'parameter-row';
            newRow.innerHTML = `
                <div class="form-group">
                    <label>Parameter Name</label>
                    <input type="text" name="paramName[]" placeholder="e.g., Temperature" required>
                </div>
                <div class="form-group">
                    <label>Type</label>
                    <select name="paramType[]" class="param-type-select" required>
                        <option value="">Select type</option>
                        <option value="Date Time">Date Time</option>
                        <option value="Alpha">Alpha</option>
                        <option value="Numeric">Numeric</option>
                        <option value="Float">Float</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Unit</label>
                    <input type="text" name="paramUnit[]" class="param-unit-input" placeholder="e.g., °C">
                </div>
                <button type="button" class="btn btn-danger remove-param" style="margin-top: 1.5rem;">Remove</button>
            `;
            parametersList.appendChild(newRow);
            attachTypeUnitListeners();
        });

        // Also attach listeners after DOMContentLoaded for initial row
        document.addEventListener('DOMContentLoaded', attachTypeUnitListeners);

        // Remove parameter row (delegate event)
        parametersList.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-param')) {
                const rows = parametersList.querySelectorAll('.parameter-row');
                if (rows.length > 1) {
                    e.target.closest('.parameter-row').remove();
                } else {
                    alert('At least one parameter is required.');
                }
            }
        });

        // Handle form submission
        presetForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get form data
            const formData = new FormData(presetForm);
            // Show loading state
            const submitBtn = presetForm.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;
            submitBtn.textContent = 'Saving...';
            submitBtn.disabled = true;

            // Send data to backend
            $.ajax({
                url: 'do_save_preset.php',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        alert(response.message || 'Preset parameters saved successfully!');
                        modal.style.display = 'none';
                        document.body.style.overflow = 'auto';
                        presetForm.reset();
                        // Reset to one parameter row
                        parametersList.innerHTML = `
                            <div class="parameter-row">
                                <div class="form-group">
                                    <label>Parameter Name</label>
                                    <input type="text" name="paramName[]" placeholder="e.g., Temperature" required>
                                </div>
                                <div class="form-group">
                                    <label>Type</label>
                                    <select name="paramType[]" class="param-type-select" required>
                                        <option value="">Select type</option>
                                        <option value="Date Time">Date Time</option>
                                        <option value="Alpha">Alpha</option>
                                        <option value="Numeric">Numeric</option>
                                        <option value="Float">Float</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Unit</label>
                                    <input type="text" name="paramUnit[]" class="param-unit-input" placeholder="e.g., °C">
                                </div>
                                <button type="button" class="btn btn-danger remove-param" style="margin-top: 1.5rem;">Remove</button>
                            </div>
                        `;
                    } else {
                        alert(response.message || 'Error saving preset parameters.');
                    }
                },
                error: function(xhr, status, error) {
                    alert('Error saving preset parameters: ' + error);
                },
                complete: function() {
                    submitBtn.textContent = originalText;
                    submitBtn.disabled = false;
                }
            });
        });
    </script>
</body>
</html>