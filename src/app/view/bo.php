<?php

use src\app\Controller\UserController;
use src\app\user\AppUser;
use src\Core\Utils\Debug;
use src\Core\Config\Config;

$skills = $entities['skills']?? null;
$items = $entities['items']?? null;
?>

<div class="container px-4 py-5" id="featured-2">
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

<div class="row g-4 py-5 row-cols-1 row-cols-lg-2">

<div class="col">
  <button type="button" class="collapsible btn btn-primary" id="boAddSkillButton">Add skill</button>
  <div class="content">
    <form class="postform" action="index.php?page=addskill" method="post">
    <div class="form-group">
      <label for="name">Name</label>
      <input type="text" class="form-control" name="name" aria-describedby="name" placeholder="Enter name" minlength="3" required>
    </div>
    <div class="form-check">
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
    </form>
  </div>
</div>

<div class="col">
  <button type="button" class="collapsible btn btn-primary" id="boAddItemButton">Add Item to Skill</button>
  <div class="content">
    <form class="postform" action="index.php?page=additem" method="post" class="was-validated">
    <div class="form-group">
      <label for="name">Name</label>
      <input type="text" class="form-control" name="name" placeholder="Enter name" aria-describedby="name" minlength="3" required>
    </div>
    <div class="form-group">
      <label for="description">Description</label>
      <input type="text" class="form-control" name="description" placeholder="Description" minlength="3"  required>
    </div>
    <div class="form-group">
      <label for="further">Further</label>
      <input type="text" class="form-control" name="further" placeholder="further">
    </div>
    <div class="form-group">
      <label for="skill_id">Skill</label>
      <select name="skill_id" class="form-control">
          <?php
          foreach($skills as $skill){
            echo "<option value={$skill->id}>{$skill->name}</option>";
          }
          ?>
      </select>
    </div>
    <div class="form-check">
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
    </form>
  </div>
</div>

<div class="col">
  <button type="button" class="collapsible btn btn-primary" id="boAddUrlToItemButton">Add Url to Item/Skill</button>
  <div class="content">
    <form class="postform" action="index.php?page=addurltoitem" method="post">
    <div class="form-group">
      <label for="name">Name</label>
      <input type="text" class="form-control" name="name" minlength="3" aria-describedby="name" placeholder="Enter name" required>
    </div>
    <div class="form-group">
      <label for="url">Url</label>
      <input type="text" class="form-control" name="url" minlength="3" placeholder="Url" required>
    </div>
    <div class="form-group">
      <label for="item_id">Item</label>
      <select name="item_id" class="form-control">
          <?php
          echo "<option value=''></option>";
          foreach($items as $item){
            echo "<option value={$item->id}>{$item->name}</option>";
          }
          ?>
      </select>
    </div>
    <div class="form-group">
      <label for="skill_id">Skill</label>
      <select name="skill_id" placeholder="skill_id" class="form-control" id="skill_id">
          <?php
          foreach($skills as $skill){
            echo "<option value={$skill->id}>{$skill->name}</option>";
          }
          ?>
      </select>
    </div>
    <div class="form-check">
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
    </form>
  </div>
</div>

<div class="col">
  <button type="button" class="collapsible btn btn-primary" id="boAddNoteButton">Add Note</button>
  <div class="content">
    <form class="postform" action="index.php?page=addnote" method="post" class="was-validated">
    <div class="form-group">
      <label for="name">Name</label>
      <input type="text" class="form-control rounded text-black" name="name" minlength="3" aria-describedby="name" placeholder="Enter name" required>
    </div>
    <div class="form-group">
      <label for="description">Description</label>
      <input type="text" class="form-control rounded text-black" name="description" minlength="3"  placeholder="description" rquired>
    </div>
    <div class="form-check">
    </div>
    <button type="submit" id="btnAddNote" class="btn-primary btn text-white">Submit</button>
  </div>
</div>

<script>
var coll = document.getElementsByClassName("collapsible");
var i;
for (i = 0; i < coll.length; i++) {
  coll[i].addEventListener("click", function() {
    this.classList.toggle("active");
    var content = this.nextElementSibling;
    if (content.style.display === "block") {
      content.style.display = "none";
    } else {
      content.style.display = "block";
    }
  });
}

boAddSkillButton = document.getElementById('boAddSkillButton');
boAddSkillButton.addEventListener('click', e => {});
boAddSkillButton.dispatchEvent(new Event('click'));
boAddSkillButton.dispatchEvent(new Event('click'));

boAddItemButton = document.getElementById('boAddItemButton');
boAddItemButton.addEventListener('click', e => {});
boAddItemButton.dispatchEvent(new Event('click'));
boAddItemButton.dispatchEvent(new Event('click'));

boAddUrlToItemButton = document.getElementById('boAddUrlToItemButton');
boAddUrlToItemButton.addEventListener('click', e => {});
boAddUrlToItemButton.dispatchEvent(new Event('click'));
boAddUrlToItemButton.dispatchEvent(new Event('click'));

boAddNoteButton = document.getElementById('boAddNoteButton');
boAddNoteButton.addEventListener('click', e => {});
boAddNoteButton.dispatchEvent(new Event('click'));
boAddNoteButton.dispatchEvent(new Event('click'));
</script>

</div>
</div>
