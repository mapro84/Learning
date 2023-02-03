<?php
use src\Core\Utils\Debug;
use src\app\Controller\UserController;

$notes = $entities['notes']?? null;
$messages = $entities['messages']?? null;

$admin = false;
if(UserController::getAdminConnection() === true){
    $admin = true;
}
?>

<div class="container px-4 py-5" id="featured-3">
  <?php if(!empty($messages['info'])){ ?>
    <div class="alert alert-success" role="alert">
    <?php
          echo $messages['info'];
    ?>
    </div>
    <?php }elseif(!empty($messages['error'])){ ?>
    <div class="alert alert-danger" role="alert">
    <?php
          echo $messages['error'];
          ?>
    </div>
  <?php } ?>
<div class="row g-4 py-5 row-cols-1 row-cols-lg-12">

<?php
foreach ($notes as $note){
  echo '<div class="col">';
  $deleteButton = '<form class="form-inline" method="post" action="index.php?page=deletenote" ' .
  'onsubmit="return confirm(\'Do you confirm to delete ' . $note->name. ' note?\');">' .
  '<input type="hidden" name="note_id" value='.$note->id.'>' . 
  '<button class="btn" ><i class="fa fa-trash fa-2xs"></i></button></form>';
  $editButton = '<button type="button" class="btn" data-bs-toggle="modal" data-bs-target="#editModal" 
  data-bs-id="' . $note->id . '" data-bs-name="' . $note->name . '" data-bs-description="' . $note->description . '">'.
  '<i class="fa fa-edit"></i></button>';
  echo $admin === true ? $editButton : '';
  echo $admin === true ? $deleteButton : '';
  echo '<span class="text-row" >';
  echo '<span class="subtitle">'.$note->name.': </span>';
  echo $note->description;
  echo '</span></div>';
}
?>
</div>
</div>

<!-- MODAL -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="editModalLabel">Edit Item</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form method="post" action="index.php?page=updatenote">
				  <input type="hidden" name="id" id="id" value=''>
          <div class="mb-3">
            <label for="name" class="col-form-label">Name:</label>
            <input name="name" id="name" type="text" class="form-control">
          </div>
          <div class="mb-3">
            <label for="message-text" class="col-form-label">Description:</label>
            <textarea name="description" id="description" class="form-control"></textarea>
          </div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
						<button type="submit" class="btn btn-primary">Update Item</button>
					</div>
        </form>
      </div>

    </div>
  </div>
</div>

<script>
const editModal = document.getElementById('editModal')
editModal.addEventListener('show.bs.modal', event => {
  // Button that triggered the modal
  const button = event.relatedTarget
  // Extract info from data-bs-* attributes
	const id = button.getAttribute('data-bs-id')
  const name = button.getAttribute('data-bs-name')
	const title = name.substring(0,15)+'...'
	const description = button.getAttribute('data-bs-description')
	//const description = document.getElementById(descriptionId).value
  // If necessary, you could initiate an AJAX request here
  // and then do the updating in a callback.

  // Update the modal's content.
  const modalTitle = editModal.querySelector('.modal-title')
	const modalBodyInputId = editModal.querySelector('.modal-body #id')
	const modalBodyInputName = editModal.querySelector('.modal-body #name')
	const modalBodyInputDescription = editModal.querySelector('.modal-body #description')

  modalTitle.textContent = `Note ${title}`
	modalBodyInputId.value = id
  modalBodyInputName.value = name
	modalBodyInputDescription.value = description
})
</script>