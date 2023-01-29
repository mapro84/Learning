<div class="container px-4 py-5" id="featured-3">

<div class="container">
    <?php
    $errors = $entities['messages']['errors'] ?? [];
    $infos = $entities['messages']['infos'] ?? [];
    foreach ($errors as $error)
    { ?>
        <div class="alert alert-danger" role="alert">
            <?php
            echo $error;
            ?>
        </div>
    <?php }
    foreach ($infos as $info)
    {
    ?>
        <div class="alert alert-success" role="alert">
            <?php
            echo $info;
            ?>
        </div>
    <?php } ?>

<div class="row g-4 py-5 row-cols-1 row-cols-lg-3">
<nav class="navbar navbar-expand-lg navbar-light bg-light">
<div class="head-menu collapse navbar-collapse" id="navbarSupportedContent">

<form class="postform" action="index.php?page=login" method="post">
  <div class="form-group">
    <label for="username">Username</label>
    <input type="text" class="form-control" id="username" name="username" aria-describedby="username" placeholder="Enter username">
    <small id="username" class="form-text text-muted">We'll never share your username with anyone else.</small>
  </div>
  <div class="form-group">
    <label for="password">Password</label>
    <input type="password" class="form-control" id="password" name="password" placeholder="Password">
  </div>
  <div class="form-check">
    <input type="checkbox" class="form-check-input" id="exampleCheck1">
    <label class="form-check-label" for="exampleCheck1">Check me out</label>
  </div>
  <button type="submit" class="btn btn-primary">Submit</button>
</form>

</div>
</nav>
</div>
</div>