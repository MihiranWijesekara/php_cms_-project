<?php
include "includes/db.php";
?>

<?php
include "includes/header.php";
?>

<!-- Navigation -->
<?php
include "includes/navigation.php";
?>

<?php 
if(isset($_POST['liked'])){
    $post_id = $_POST['post_id'];
    $user_id = $_POST['user_id'];
     //1 =  Fetching the right post
    $query = "SELECT * FROM posts WHERE post_id = $post_id";
    $postResult = mysqli_query($connection,$query);
    $post = mysqli_fetch_array($postResult);
    $likes = $post['likes'];


    //2 = UPDATE PPOST WITH LIKES

    mysqli_query($connection,"UPDATE posts SET likes=$like+1 WHERE post_id = $post_id ");

    //3 = CREATE LIKES FOR POST

    mysqli_query($connection,"INSERT INTO likes(user_id,post_id) VALUES(user_id,post_id) ");
    exit();
    
}


if(isset($_POST['unliked'])){
    $post_id = $_POST['post_id'];
    $user_id = $_POST['user_id'];
    //1 =  Fetching the right post
    $query = "SELECT * FROM posts WHERE post_id = $post_id";
    $postResult = mysqli_query($connection,$query);
    $post = mysqli_fetch_array($postResult);
    $likes = $post['likes'];

    //2 = UPDATE POST WITH LIKES

    mysqli_query($connection,"DELETE FROM likes WHERE post_id=$post_id AND user_id=$user_id");

    //3 = UPDATE PPOST WITH LIKES

    mysqli_query($connection,"UPDATE posts SET likes=$like-1 WHERE post_id = $post_id ");

    exit();
}

?>

<!-- Page Content -->
<div class="container">

    <div class="row">

        <!-- Blog Entries Column -->
        <div class="col-md-8">

            <?php

            if (isset($_GET['p_id'])) {
                $the_post_id = $_GET['p_id'];

                $view_query = "UPDATE posts set post_views_count = post_views_count + 1 WHERE post_id = $the_post_id";
                $send_query = mysqli_query($connection, $view_query);

                if (!$send_query) {
                    die("Query failed " . mysqli_error($connection));
                }
                if(isset($_SESSION['user_role']) && $_SESSION['user_role'] =='admin' ){
                    $query = "SELECT * FROM posts WHERE post_id = $the_post_id ";

                } else {
                    $query = "SELECT * FROM posts WHERE post_id = $the_post_id AND post_status = 'published' ";

                }


                $select_all_posts_query = mysqli_query($connection, $query);

                if(mysqli_num_rows($select_all_posts_query) < 1 ) {
                    echo " <h1 class='text-center'> No posts available </h1> ";
                
                } else {

                while ($row = mysqli_fetch_assoc($select_all_posts_query)) {

                    $post_title = $row['post_title'];
                    $post_author = $row['post_author'];
                    $post_date = $row['post_date'];
                    $post_image = $row['post_image'];
                    $post_content = $row['post_content'];

            ?>

                    <h1 class="page-header">
                       Posts
                        
                    </h1>

                    <!-- First Blog Post -->
                    <h2>
                        <a href="post.php?p_id=<?php echo $post_id; ?>"><?php echo $post_title   ?> </a>
                    </h2>
                    <p class="lead">
                        by <a href="author_posts.php?author=<?php echo $post_author ?>&p_id=<?php echo $post_id; ?>"><?php echo $post_author ?></a>
                    </p>
                    <p><span class="glyphicon glyphicon-time"></span><?php echo $post_date ?></p>
                    <hr>

                    <img class="img-responsive" src="images/<?php echo $post_image ?>" alt="">
                    <hr>
                    <p><?php echo $post_content ?></p>


                    <hr>

                    <div class="row">
                        <p class="pull-right"> <a class="like" href="#"> <span class="glyphicon glyphicon-thumbs-up"></span>  Like</a> </p>
                    </div>

                    <div class="row">
                        <p class="pull-right"> <a class="unlike" href="#"> <span class="glyphicon glyphicon-thumbs-down"></span> Unlike</a> </p>
                    </div>

                    <div class="row">
                        <p class="pull-right"> <a href="">Like: 10</a> </p>
                    </div>
                    <div class="clearfix"></div>

                <?php   }


                ?>

                                <!-- Blog Comments -->

                                <?php
                if (isset($_POST['create_comment'])) {

                    $the_post_id = $_GET['p_id'];
                    $comment_author = $_POST['comment_author'];
                    $comment_email = $_POST['comment_email'];
                    $comment_content = $_POST['comment_content'];

                    if (!empty($comment_author) && !empty($comment_email) && !empty($comment_content)) {

                        $query = "INSERT INTO comments (comment_pos, comment_author, comment_email, comment_content, comment_status, comment_date) 
                    VALUES ('$the_post_id', '$comment_author', '$comment_email', '$comment_content', 'unapproved', now())";

                        $create_comment_query = mysqli_query($connection, $query);

                        if (!$create_comment_query) {
                            die('QUERY FAILED' . mysqli_error($connection));
                        }


                    } else {
                        echo "<script>  alert('Fields cannot be empty')  </script>";
                    }
                }


                ?>





                <!-- Comments Form -->
                <div class="well">
                    <h4>Leave a Comment:</h4>
                    <form action="" method="post" role="form">

                        <div class="form-group">
                            <label for="Author"> Author </label>
                            <input type="text" name="comment_author" class="form-control" name="comment_author">
                        </div>

                        <div class="form-group">
                            <label for="email"> Email </label>
                            <input type="email" name="comment_email" class="form-control" name="comment_email">
                        </div>

                        <div class="form-group">
                            <label for="comment"> Your Comment </label>
                            <textarea class="form-control" name="comment_content" rows="3"></textarea>
                        </div>
                        <button type="submit" name="create_comment" class="btn btn-primary">Submit</button>
                    </form>
                </div>

                <hr>

                <!-- Posted Comments -->

                <?php
                $query = "SELECT * FROM comments WHERE comment_pos = {$the_post_id} ";
                $query .= "AND comment_status = 'approve' ";
                $query .= "ORDER BY comment_id DESC";
                $select_comment_query = mysqli_query($connection, $query);
                if (!$select_comment_query) {
                    die('Query Failed: ' . mysqli_error($connection));
                }
                while ($row = mysqli_fetch_array($select_comment_query)) {
                    $comment_date = $row['comment_date'];
                    $comment_content = $row['comment_content'];
                    $comment_author = $row['comment_author'];
                ?>


                    <!-- Comment -->
                    <div class="media">
                        <a class="pull-left" href="#">
                            <img class="media-object" src="http://placehold.it/64x64" alt="">
                        </a>
                        <div class="media-body">
                            <h4 class="media-heading"><?php echo $comment_author  ?>
                                <small><?php echo $comment_date  ?></small>
                            </h4>
                            <?php echo $comment_content  ?>
                        </div>
                    </div>



            <?php  }
            } } else {
                header("Location: index.php");
            }  ?>



        </div>


    </div>



    <!-- Blog Sidebar Widgets Column -->
    <div class="col-md-4">

        <!-- Blog Search Well -->
        <div class="well">
            <h4>Blog Search</h4>
            <div class="input-group">
                <input type="text" class="form-control">
                <span class="input-group-btn">
                    <button class="btn btn-default" type="button">
                        <span class="glyphicon glyphicon-search"></span>
                    </button>
                </span>
            </div>
            <!-- /.input-group -->
        </div>

        <!-- Blog Categories Well -->
        <div class="well">
            <h4>Blog Categories</h4>
            <div class="row">
                <div class="col-lg-6">
                    <ul class="list-unstyled">
                        <li><a href="#">Category Name</a>
                        </li>
                        <li><a href="#">Category Name</a>
                        </li>
                        <li><a href="#">Category Name</a>
                        </li>
                        <li><a href="#">Category Name</a>
                        </li>
                    </ul>
                </div>
                <div class="col-lg-6">
                    <ul class="list-unstyled">
                        <li><a href="#">Category Name</a>
                        </li>
                        <li><a href="#">Category Name</a>
                        </li>
                        <li><a href="#">Category Name</a>
                        </li>
                        <li><a href="#">Category Name</a>
                        </li>
                    </ul>
                </div>
            </div>
            <!-- /.row -->
        </div>

        <!-- Side Widget Well -->
        <div class="well">
            <h4>Side Widget Well</h4>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Inventore, perspiciatis adipisci accusamus laudantium odit aliquam repellat tempore quos aspernatur vero.</p>
        </div>

    </div>

</div>
<!-- /.row -->

<hr>

<!-- Footer -->
<footer>
    <div class="row">
        <div class="col-lg-12">
            <p>Copyright &copy; Your Website 2014</p>
        </div>
    </div>
    <!-- /.row -->
</footer>

</div>
<!-- /.container -->

<!-- jQuery -->
<script src="js/jquery.js"></script>

<!-- Bootstrap Core JavaScript -->
<script src="js/bootstrap.min.js"></script>

    <script>
        $(document).ready(function(){
            var post_id = <?php echo $the_post_id; ?>
            var user_id = 3;
            //Like
            $('.like').click(function(){
                $.ajax({
                    url: "posts.php?p_id=<?php echo $the_post_id; ?>",
                    type: 'post',
                    data: {
                        'liked': 1,
                        'post_id': post_id,
                        'user_id' : user_id
                    }
                })
            });
            //Unlike
            $('.unlike').click(function(){
                $.ajax({
                    url: "/cms/posts.php?p_id=<?php echo $the_post_id; ?>",
                    type: 'post',
                    data: {
                        'unliked': 1,
                        'post_id': post_id,
                        'user_id' : user_id
                    }
                })
            });
        });
    </script>

</body>

</html>