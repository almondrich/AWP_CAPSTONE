<?php
include '../Actions/connection.php';
include '../Actions/items/sidenav.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Inventory System</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="../../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="../../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="../../plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../../dist/css/adminlte.min.css">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>  
    </ul>
    <ul class="navbar-nav ml-auto">
      <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->


  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Unit List</h1>
          </div>
        
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <!-- /.card -->

            <div class="card">
              <div class="card-header">
                <button type="button" id="add" class="btn btn-primary" data-toggle="modal" data-target="#modal-default">
                  Create
                </button>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>Units</th>
                    <th width="200">Actions</th>
                 
                  </tr>
                  </thead>
                  <tbody>
                  
                    <?php
                      $q = mysqli_query($con,"SELECT * FROM unit order by unitdesc desc");
                      while ($rows=mysqli_fetch_array($q)) {
                    ?>
                    <tr>
                       <td><?php echo $rows[1]; ?></td>
                       <td>
                          <button class="btn btn-danger edit" value="<?php echo $rows[0]; ?>">Edit</button>
                          <button class="btn btn-success delete" value="<?php echo $rows[0]; ?>">Delete</button>
                        </td>
                    </tr>
                    <?php
                      }
                    ?>
                  </tbody>
                  <tfoot>
                  <tr>
                    <th>Units</th>
                    <th>Actions</th>
                  </tr>
                  </tfoot>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <div class="modal fade" id="modal-default">
        <div class="modal-dialog">
          <div class="modal-content">
        
            <div class="modal-header">
              <h4 class="modal-title">Add Unit</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form id="frm">
                <div class="card-body">
                  <div class="form-group">
                    <label for="exampleInputEmail1">Description</label>
                    <input type="text" class="form-control" id="unit" name="unit">
                  </div>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
              <input type="hidden" name="p_id" id="p_id">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary" id="save">Save changes</button>
            </div>
             </form>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="../../plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- DataTables  & Plugins -->
<script src="../../plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../../plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="../../plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="../../plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="../../plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="../../plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="../../plugins/jszip/jszip.min.js"></script>
<script src="../../plugins/pdfmake/pdfmake.min.js"></script>
<script src="../../plugins/pdfmake/vfs_fonts.js"></script>
<script src="../../plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="../../plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="../../plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
<!-- AdminLTE App -->
<script src="../../dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->

<script>
  $(function () {
    $("#example1").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });
  });
</script>
</body>
</html>

<script>
  $(document).ready(function() {
        $("#add").click(function(e) {
          e.preventDefault();
            $("#frm")[0].reset();
            $("#unit").val("");
            $("#p_id").val("");
        });

        $("#save").click(function(e) {
          e.preventDefault();
          var formData = $("#frm").serialize();

          $.ajax({
            url: '../Actions/Unit/AddUnit.php',
            type: 'POST',
            data: formData,
            success: function(response) {
                if (response == "1") {
                    showMessage("Unit successfully added!", "success");
                } else if (response == "2") {
                    showMessage("Unit successfully updated!", "success");
                } else {
                    showMessage(response, "error");
                }
                setTimeout(function() {
                    location.reload();
                }, 1500);
            },
            error: function(error) {
                showMessage("An error occurred. Please try again.", "error");
                console.log(error);
            }
          });
        });

        $(".delete").click(function(e) {
          e.preventDefault();
          var cid = $(this).val();

          if (confirm('Are you sure you want to delete this data?')) {
              $.ajax({
                    type: "POST",
                    url: '../Actions/Unit/delUnit.php',
                    data: { 'cid': cid },
                    success: function(response) {
                       showMessage(response, "success");
                       setTimeout(function() {
                           location.reload();
                       }, 1500);
                    }
              });
          }
        });

        $(".edit").click(function(e) {
            e.preventDefault();
            var cid = $(this).val();
          
            $.ajax({
                    type: "POST",
                    url: '../Actions/Unit/fetch.php',
                    data: { 'cid': cid },
                    dataType: 'json',
                    success: function(response) {
                        if (response) {
                            $("#unit").val(response.unitDesc);
                            $("#p_id").val(response.uid);
                            $('#modal-default').modal('show');
                        } else {
                            showMessage("Error: Unable to fetch unit data.", "error");
                        }
                    }
            });
        });

        function showMessage(message, type) {
            var alertType = type === "success" ? "alert-success" : "alert-danger";
            var alertDiv = `<div class="alert ${alertType} alert-dismissible fade show" role="alert">
                                ${message}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>`;
            $("body").prepend(alertDiv);
            setTimeout(function() {
                $(".alert").alert('close');
            }, 3000);
        }
  });
</script>
