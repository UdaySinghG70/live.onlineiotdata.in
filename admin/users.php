<?php
session_start();

if(isset($_SESSION['admin_name'])==false){
    echo "Invalid Login";
    header('Location: login.php');
    return;
}

$admin_name=$_SESSION['admin_name'];

include_once '../model/adminlogin_dao.php';
$aldao=new AdminLoginDao();

$adminDetails=$aldao->getAdminByUserName($admin_name);
if($adminDetails==null){
    echo "Invalid Login";
    header('Location: login.php?msg=error&admin_name='.$admin_name);
    return;
}

include_once '../model/admindao.php';
$adao=new AdminDao();

$pg=1;
if(isset($_REQUEST['pg'])){
	$pg=$_REQUEST['pg'];
	if($pg<=0){
		$pg=1;
	}
}

$recordsToDisplay = 10;
$recordCount=$adao->getUserCount();
$linkCount = $recordCount % $recordsToDisplay == 0 ? ( int )( $recordCount / $recordsToDisplay ) : ( int ) ( $recordCount / $recordsToDisplay ) + 1;

if($pg>$linkCount){
	$pg=$linkCount;
}

$starttingRecord = ($pg-1) * $recordsToDisplay;
$usersArr=$adao->getUsersWithLimit($starttingRecord, $recordsToDisplay);

if($usersArr==null){
    echo "<a href='create_user.php'>Create User</a>";
    return;
}

if($linkCount - $pg > 10){
	$linkStart=$pg;
}else{
	if($linkCount > 10){
		$linkStart = $linkCount - 10;
	}else {
		$linkStart=1;
	}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Manage Users - Cloud Data Monitoring</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="icon" type="image/png" href="../images/icons/favicon.png"/>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="css/admin-style.css">
    <style>
        /* Users page specific styles */
        .users-container {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .page-title {
            font-size: 1.5rem;
            color: #1e293b;
            font-weight: 600;
        }

        .users-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-bottom: 1.5rem;
        }

        .users-table th,
        .users-table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
        }

        .users-table th {
            background: #f8fafc;
            font-weight: 500;
            color: #64748b;
            font-size: 0.875rem;
            white-space: nowrap;
	}

        .users-table tr:hover td {
            background: #f8fafc;
        }

        .users-table td {
            color: #1e293b;
            font-size: 0.875rem;
	}

        .action-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            font-size: 0.875rem;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s ease;
            background: #f1f5f9;
            color: #0067ac;
            border: none;
            cursor: pointer;
        }

        .action-btn:hover {
            background: #e2e8f0;
        }

        .action-btn.delete {
            color: #dc2626;
        }

        .action-btn.delete:hover {
            background: #fee2e2;
        }

        .pagination {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-top: 1.5rem;
	}
	 
        .pagination-btn {
            display: inline-flex;
            align-items: center;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            font-size: 0.875rem;
            font-weight: 500;
            background: #f1f5f9;
            color: #0067ac;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .pagination-btn:hover:not(:disabled) {
            background: #e2e8f0;
	}

        .pagination-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
	}

        .pagination-input {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-left: auto;
        }

        .page-input {
            width: 60px;
            padding: 0.5rem;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
		text-align: center;
            font-size: 0.875rem;
	}

        .page-input:focus {
            outline: none;
            border-color: #0067ac;
            box-shadow: 0 0 0 3px rgba(0, 103, 172, 0.1);
        }

        @media (max-width: 768px) {
            .users-container {
                padding: 1rem;
                overflow-x: auto;
	}

            .page-header {
                flex-direction: column;
                gap: 1rem;
                align-items: flex-start;
            }

            .users-table {
                min-width: 600px;
            }

            .pagination {
                flex-wrap: wrap;
                gap: 0.5rem;
            }

            .pagination-input {
                width: 100%;
                justify-content: flex-end;
                margin-top: 0.5rem;
            }
}
</style>
</head>
<body>
		<?php include_once 'admin_header.php';?>
		
    <main class="dashboard">
        <div class="users-container">
            <div class="page-header">
                <h1 class="page-title">Manage Users</h1>
                <a href="create_user.php" class="action-btn">
                    <span class="material-icons">person_add</span>
                    Add New User
                </a>
            </div>

            <div class="table-responsive">
                <table class="users-table">
                        <thead>
                        <tr>
                            <th>User Name</th>
                            <th>Department</th>
                            <th>Email</th>
                            <th>Mobile</th>
                            <th>City</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach($usersArr as $user): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($user->user_name); ?></td>
                            <td><?php echo htmlspecialchars($user->department_name); ?></td>
                            <td><?php echo htmlspecialchars($user->email_id); ?></td>
                            <td><?php echo htmlspecialchars($user->mobile); ?></td>
                            <td><?php echo htmlspecialchars($user->city); ?></td>
                            <td>
                                <button class="action-btn edit_user" data-id="<?php echo $user->id; ?>">
                                    <span class="material-icons">edit</span>
                                    Edit
                                </button>
                                <button class="action-btn delete delete_user" data-id="<?php echo $user->id; ?>">
                                    <span class="material-icons">delete</span>
                                    Delete
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        </tbody>
                	</table>
            </div>

            <div class="pagination">
                <button class="pagination-btn prev_btn" <?php echo $pg == 1 ? 'disabled' : ''; ?>>
                    <span class="material-icons">chevron_left</span>
                    Previous
                </button>
                
                <button class="pagination-btn next_btn" <?php echo $pg >= $linkCount ? 'disabled' : ''; ?>>
                    Next
                    <span class="material-icons">chevron_right</span>
                </button>

                <input type="hidden" value="<?php echo $pg; ?>" name="current_page">

                <div class="pagination-input">
                    <span>Page</span>
                    <input type="number" value="<?php echo $pg; ?>" name="go_to_page" class="page-input" min="1" max="<?php echo $linkCount; ?>">
                    <span>of <?php echo $linkCount; ?></span>
                    <button class="pagination-btn btn_goto_page">Go</button>
                </div>
			</div>
		</div>
    </main>

	<script src="../vendor/jquery/jquery-3.2.1.min.js"></script>
	<script src="../js/jquery-ui.js"></script>
    <script>
        $(document).ready(function() {
            // Edit user
            $('.edit_user').click(function() {
                var userId = $(this).data('id');
                window.location.href = 'edit_user.php?id=' + userId;
            });

            // Delete user
            $('.delete_user').click(function() {
                if (confirm('Are you sure you want to delete this user?')) {
                    var userId = $(this).data('id');
                    // Add your delete logic here
                }
            });

            // Pagination
            $('.prev_btn').click(function() {
                if (!$(this).prop('disabled')) {
                    var currentPage = parseInt($('input[name="current_page"]').val());
                    window.location.href = 'users.php?pg=' + (currentPage - 1);
                }
            });

            $('.next_btn').click(function() {
                if (!$(this).prop('disabled')) {
                    var currentPage = parseInt($('input[name="current_page"]').val());
                    window.location.href = 'users.php?pg=' + (currentPage + 1);
                }
            });

            $('.btn_goto_page').click(function() {
                var page = parseInt($('input[name="go_to_page"]').val());
                if (page >= 1 && page <= <?php echo $linkCount; ?>) {
                    window.location.href = 'users.php?pg=' + page;
                }
            });
	});
</script>
</body>
</html>