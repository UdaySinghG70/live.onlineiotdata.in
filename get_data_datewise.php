<?php
session_start();
include_once 'model/datadao.php';
include_once 'model/admindao.php';

if(!isset($_SESSION['user_name'])) {
	echo "Invalid Access";
	return;
}

$device_id = $_POST['device_id'];
$startdate = $_POST['start_date'];
$enddate = $_POST['end_date'];
$start_time = isset($_POST['start_time']) ? $_POST['start_time'] . ':00' : '00:00:00';
$end_time = isset($_POST['end_time']) ? $_POST['end_time'] . ':00' : '23:59:59';
$pg = isset($_POST['pg']) ? intval($_POST['pg']) : 1;
$records_per_page = 50;

$dao = new DataDAO();

// Get parameters for this device
$param_query = "SELECT param_name, position, unit FROM logparam 
                WHERE device_id = ? 
                ORDER BY position";
$param_params = array($device_id);
$parameters = $dao->getData($param_query, $param_params);

if(empty($parameters)) {
    echo "<div class='alert alert-info'>No parameters defined for this device</div>";
    return;
}

// Get total count of records with time filtering
$count_query = "SELECT COUNT(*) as total FROM logdata 
                WHERE device_id = ? 
                AND (
                    CASE 
                        WHEN date >= ? AND date <= ? THEN 1
                        WHEN date = ? AND time >= ? AND date = ? AND time <= ? THEN 1
                        ELSE 0
                    END
                ) = 1";
$count_params = array(
    $device_id, 
    $startdate, $enddate,           // For dates in between
    $startdate, $start_time,        // For start time check
    $enddate, $end_time            // For end time check
);
$count_result = $dao->getData($count_query, $count_params);
$total_records = $count_result[0]->total;
$total_pages = ceil($total_records / $records_per_page);

// Ensure current page is within valid range
$pg = max(1, min($pg, $total_pages));
$offset = ($pg - 1) * $records_per_page;

// Get the data with pagination and time filtering
$data_query = "SELECT date, time, data 
               FROM logdata 
               WHERE device_id = ? 
               AND (
                   CASE 
                       WHEN date >= ? AND date <= ? THEN 1
                       WHEN date = ? AND time >= ? AND date = ? AND time <= ? THEN 1
                       ELSE 0
                   END
               ) = 1
               ORDER BY date DESC, time DESC
               LIMIT ? OFFSET ?";
$data_params = array(
    $device_id,
    $startdate, $enddate,           // For dates in between
    $startdate, $start_time,        // For start time check
    $enddate, $end_time,           // For end time check
    $records_per_page, $offset
);
$data = $dao->getData($data_query, $data_params);

if(empty($data)) {
	echo "<div class='alert alert-info'>No data found for the specified date and time range</div>";
	return;
}
?>

<style>
.data-table-container {
	position: relative;
	margin-top: 20px;
	border-radius: 10px;
	background: #fff;
	box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
	overflow-x: auto;
	overflow-y: hidden;
	-webkit-overflow-scrolling: touch;
	scrollbar-width: thin;
	scrollbar-color: #0067ac #f0f0f0;
}

.data-table-container::-webkit-scrollbar {
	height: 8px;
}

.data-table-container::-webkit-scrollbar-track {
	background: #f0f0f0;
	border-radius: 4px;
}

.data-table-container::-webkit-scrollbar-thumb {
	background: #0067ac;
	border-radius: 4px;
}

.data-table {
	width: max-content;
	min-width: 100%;
	border-collapse: collapse;
	font-size: 15px;
	white-space: nowrap;
}

.data-container {
	flex: none;
	margin-top: 20px;
	background: #fff;
	border-radius: 10px;
	padding: 20px;
}

.data-table th {
	background: linear-gradient(45deg, #0067ac, #0088e0);
	color: #fff;
	font-weight: 600;
	padding: 12px 16px;
	text-align: center;
	border: none;
	min-width: 120px;
	font-size: 16px;
	line-height: 1.4;
	word-wrap: break-word;
	white-space: normal;
	vertical-align: middle;
	height: auto;
	position: sticky;
	top: 0;
	z-index: 1;
}

.data-table th .unit-text {
	display: block;
	font-size: 14px;
	font-weight: normal;
	opacity: 0.9;
	margin-top: 4px;
	word-wrap: break-word;
}

.data-table th:first-child {
	position: sticky;
	left: 0;
	z-index: 2;
	min-width: 150px;
}

.data-table th:last-child {
	border-top-right-radius: 10px;
}

.data-table td {
	padding: 14px 16px;
	border-bottom: 1px solid #edf2f7;
	color: #2d3748;
	text-align: center;
	vertical-align: middle;
	font-size: 15px;
}

.data-table tbody tr:nth-child(even) {
	background-color: #f8fafc;
}

.data-table tbody tr:nth-child(odd) {
	background-color: #ffffff;
}

.data-table tbody tr:hover {
	background-color: #ebf8ff;
	transition: background-color 0.2s ease;
}

.data-table tbody tr:last-child td {
	border-bottom: none;
}

.data-table td:not(:first-child) {
	font-family: 'Consolas', monospace;
	font-size: 15px;
}

.data-table td:first-child {
	position: sticky;
	left: 0;
	z-index: 1;
	background: #fff;
	font-weight: 500;
	color: #2d3748;
	font-size: 15px;
}

.data-table tbody tr:nth-child(even) td:first-child {
	background: #f8fafc;
}

.data-table tbody tr:hover td:first-child {
	background: #ebf8ff;
}

.export-buttons {
    display: flex;
    gap: 15px;
    margin-bottom: 15px;
}

.export-btn {
    display: inline-flex;
    align-items: center;
    color: #fff;
    border: none;
    padding: 12px 24px;
    border-radius: 6px;
    font-weight: 500;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
}

.export-btn.xlsx {
    background: linear-gradient(45deg, #1D6F42, #2E844A);
}

.export-btn.xlsx:hover {
    background: linear-gradient(45deg, #185C37, #1D6F42);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(29, 111, 66, 0.2);
}

.export-btn.csv {
    background: linear-gradient(45deg, #4A5568, #2D3748);
}

.export-btn.csv:hover {
    background: linear-gradient(45deg, #2D3748, #1A202C);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(45, 55, 72, 0.2);
}

.export-btn svg {
    margin-right: 8px;
    width: 16px;
    height: 16px;
}

@media (max-width: 768px) {
    .export-buttons {
        flex-direction: column;
    }
    
    .export-btn {
        width: 100%;
        justify-content: center;
    }
}

.alert {
	padding: 15px 20px;
	border-radius: 8px;
	margin-bottom: 20px;
	font-size: 14px;
	text-align: center;
}

.alert-info {
	background-color: #EBF8FF;
	color: #2B6CB0;
	border: 1px solid #BEE3F8;
}

.pagination-container {
	display: flex;
	align-items: center;
	justify-content: space-between;
	margin: 20px 0;
	padding: 10px;
	background: #f8f9fa;
	border-radius: 8px;
}

.pagination-info {
	color: #4a5568;
	font-size: 14px;
	text-align: center;
}

.pagination-controls {
	display: flex;
	align-items: center;
	gap: 10px;
	justify-content: center;
}

.page-btn {
	background: #fff;
	border: 2px solid #e2e8f0;
	padding: 8px 16px;
	border-radius: 6px;
	color: #4a5568;
	font-weight: 500;
	font-size: 14px;
	cursor: pointer;
	transition: all 0.2s ease;
	min-width: 100px;
	text-align: center;
}

.page-btn:hover:not(:disabled) {
	border-color: #0067ac;
	color: #0067ac;
}

.page-btn:disabled {
	opacity: 0.5;
	cursor: not-allowed;
}

.page-btn.active {
	background: #0067ac;
	color: #fff;
	border-color: #0067ac;
}

.page-input {
	width: 60px;
	padding: 8px;
	border: 2px solid #e2e8f0;
	border-radius: 6px;
	text-align: center;
	font-size: 14px;
}

.page-input:focus {
	outline: none;
	border-color: #0067ac;
}

@media (max-width: 768px) {
	.data-table-container {
		margin: 10px -15px;
		border-radius: 0;
	}
	
	.data-table th,
	.data-table td {
		padding: 8px;
		font-size: 13px;
		min-width: 100px;
	}
	
	.data-table th:first-child {
		min-width: 130px;
	}
	
	.data-table td:first-child {
		min-width: 130px;
	}
}

@media (max-width: 480px) {
	.data-table-container {
		margin: 5px -10px;
	}
	
	.data-table th,
	.data-table td {
		padding: 6px;
		font-size: 12px;
		min-width: 90px;
	}
	
	.data-table th:first-child,
	.data-table td:first-child {
		min-width: 120px;
	}
}

/* Add touch-friendly styles */
@media (hover: none) {
	.data-table tbody tr:hover {
		background-color: transparent;
	}
	
	.page-btn:active {
		transform: scale(0.98);
	}
	
	.export-btn:active {
		transform: scale(0.98);
	}
	
	.page-input {
		font-size: 16px; /* Prevent zoom on iOS */
	}
}

/* Fix for iOS input zoom */
@supports (-webkit-touch-callout: none) {
	.page-input {
		font-size: 16px;
	}
}

/* Add horizontal scroll indicator */
.data-table-container::after {
	content: '';
	position: absolute;
	top: 0;
	right: 0;
	bottom: 0;
	width: 30px;
	background: linear-gradient(to right, transparent, rgba(255, 255, 255, 0.9));
	pointer-events: none;
	opacity: 0;
	transition: opacity 0.3s;
}

.data-table-container:hover::after {
	opacity: 1;
}

@media (max-width: 768px) {
	.data-table-container::after {
		opacity: 1;
	}
}
</style>

<div class="pagination-container">
	<div class="pagination-info">
		Showing <?php echo ($offset + 1) ?>-<?php echo min($offset + $records_per_page, $total_records) ?> 
		of <?php echo $total_records ?> records
	</div>
	<div class="pagination-controls">
		<button class="page-btn" onclick="changePage(1)" <?php echo ($pg <= 1) ? 'disabled' : ''; ?>>
			First
		</button>
		<button class="page-btn" onclick="changePage(<?php echo $pg - 1 ?>)" <?php echo ($pg <= 1) ? 'disabled' : ''; ?>>
			Previous
		</button>
		<input type="number" class="page-input" value="<?php echo $pg ?>" 
			   min="1" max="<?php echo $total_pages ?>" 
			   onchange="changePage(this.value)">
		<span>of <?php echo $total_pages ?></span>
		<button class="page-btn" onclick="changePage(<?php echo $pg + 1 ?>)" <?php echo ($pg >= $total_pages) ? 'disabled' : ''; ?>>
			Next
		</button>
		<button class="page-btn" onclick="changePage(<?php echo $total_pages ?>)" <?php echo ($pg >= $total_pages) ? 'disabled' : ''; ?>>
			Last
		</button>
	</div>
</div>

<div class="export-buttons">
    <a href="export_excel_datewise.php?device_id=<?php echo $device_id; ?>&start_date=<?php echo $startdate; ?>&end_date=<?php echo $enddate; ?>&format=xlsx" class="export-btn xlsx">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>
        Export to XLSX
    </a>
    <a href="export_excel_datewise.php?device_id=<?php echo $device_id; ?>&start_date=<?php echo $startdate; ?>&end_date=<?php echo $enddate; ?>&format=csv" class="export-btn csv">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2" />
        </svg>
        Export to CSV
    </a>
</div>

<div class="data-table-container">
	<table class="data-table">
		<thead>
			<tr>
				<th>Timestamp</th>
				<?php
				foreach($parameters as $param) {
					if ($param->position != 1) { // Skip the first parameter as it's the timestamp
						echo "<th>" . htmlspecialchars($param->param_name) . "<br><span class='unit-text'>(" . htmlspecialchars($param->unit) . ")</span></th>";
					}
				}
				?>
			</tr>
		</thead>
		<tbody>
			<?php
			foreach($data as $record) {
				$values = explode(',', $record->data);
				$timestamp = array_shift($values); // Get the timestamp
				
				// Format timestamp for display
				$formatted_timestamp = substr($timestamp, 0, 2) . "-" . 
									substr($timestamp, 2, 2) . "-" . 
									substr($timestamp, 4, 2) . " " . 
									substr($timestamp, 6, 2) . ":" . 
									substr($timestamp, 8, 2);
				
				echo "<tr>";
				echo "<td>" . $formatted_timestamp . "</td>";
				
				foreach($values as $value) {
					echo "<td>" . htmlspecialchars($value) . "</td>";
				}
				echo "</tr>";
			}
			?>
		</tbody>
	</table>
</div>

<script>
function changePage(page) {
	page = parseInt(page);
	if (isNaN(page) || page < 1 || page > <?php echo $total_pages ?>) {
		return;
	}
	
	const device_id = '<?php echo $device_id ?>';
	const start_date = '<?php echo $startdate ?>';
	const end_date = '<?php echo $enddate ?>';
	const start_time = '<?php echo $start_time ?>';
	const end_time = '<?php echo $end_time ?>';
	
	// Show loading state
	$('.msg_task').text('Loading...');
	$('.loading').show();
	
	$.ajax({
		url: 'get_data_datewise.php',
		type: 'POST',
		data: {
			device_id: device_id,
			start_date: start_date,
			end_date: end_date,
			start_time: start_time,
			end_time: end_time,
			pg: page
		},
		success: function(response) {
			$('.data').html(response);
			$('.msg_task').text('');
			$('.loading').hide();
		},
		error: function() {
			$('.msg_task').text('Error loading data. Please try again.');
			$('.loading').hide();
		}
	});
}

// Keep the smooth scrolling functionality
document.addEventListener('DOMContentLoaded', function() {
	const tableContainer = document.querySelector('.data-table-container');
	let isScrolling = false;
	let startY;
	let scrollTop;

	tableContainer.addEventListener('mousedown', function(e) {
		isScrolling = true;
		startY = e.pageY - tableContainer.offsetTop;
		scrollTop = tableContainer.scrollTop;
	});

	document.addEventListener('mousemove', function(e) {
		if (!isScrolling) return;
		
		e.preventDefault();
		const y = e.pageY - tableContainer.offsetTop;
		const walk = (y - startY) * 2;
		tableContainer.scrollTop = scrollTop - walk;
	});

	document.addEventListener('mouseup', function() {
		isScrolling = false;
	});
});
</script>


