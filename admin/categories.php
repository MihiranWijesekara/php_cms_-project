<?php include "includes/admin_header.php" ?>

<div id="wrapper">

    <!-- Navigation -->
    <?php include "includes/admin_navigation.php" ?>

    <div id="page-wrapper">
        <div class="container-fluid">
            <!-- Page Heading -->
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">
                        Welcome to admin
                        <small>Author</small>
                    </h1>
                    <div class="col-xs-6">
                        <?php  insert_categories(); ?>

                        <form action="" method="post">
                            <div class="form-group">
                                <label for="cat-title"> Add category </label>
                                <input type="text" class="form-control" name="cat_title">
                            </div>
                            <div class="form-group">
                                <input class="btn btn-primary" type="submit" name="submit" value="Add category">
                            </div>
                        </form>
                        <?php //UPDATE AND INCLUDE QUERY
                        if(isset($_GET['edit'])){
                            $cat_id = $_GET['edit'];
                            include "includes/update_categories.php";
                        } 
                        ?>
                    </div>
                    <!-- Add category Form -->
                    <div class="col-xs-6">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Category Title</th>
                                </tr>
                            </thead>
                            <tbody>
                            <!-- FIND ALL CATEGORY QUERY --> 
                                <?php findAllCategories(); ?>
                                
                                <?php 
                                //DELETE QUERY
                                if(isset($_GET['delete'])){
                                    $the_cat_id = $_GET['delete'];
                                    $query = "DELETE FROM categories WHERE cat_id = {$the_cat_id}";
                                    $delete_query = mysqli_query($connection,$query);
                                    if ($delete_query) {
                                        // Redirect to the same page after deletion
                                        header("Location: categories.php");
                                        exit; // Exit to prevent further execution
                                    } else {
                                        // Handle deletion failure
                                        echo "Deletion failed: " . mysqli_error($connection);
                                    }
                                }
                                
                                
                                ?>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include "includes/admin_footer.php" ?>
