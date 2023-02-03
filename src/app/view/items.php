<?php
use src\Core\Utils\Debug;
use src\Core\Utils\Check;
use src\app\Controller\UserController;

$admin = false;
if(UserController::getAdminConnection() === true){
    $admin = true;
}

$items = $entities['items'] ?? [];
$demos = $entities['demos'] ?? [];
$messages = $entities['messages']?? [];
$relatedUrls = $entities['relatedUrls'] ?? [];
$openaiResponse = $entities['openaiResponse'] ?? [];
$skills = $entities['skills'] ?? [];

?>

<div class="container">
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
<div class="row py-4 row-cols-1 row-cols-lg-3">
<nav class="navbar navbar-expand-lg navbar-light bg-light">
<div class="head-menu collapse navbar-collapse" id="navbarSupportedContent">
<ul class="navbar-nav mr-auto">
<?php
		foreach ($skills as $skill) {
			echo '<a href="index.php?page=skill&skill_id=' . $skill['id'] . '">';
            echo file_exists('public/img/'.$skill['logo'] ) ? '<img class="logo" src="public/img/'.$skill['logo'] .'" title="'.$skill['name'] . '">' : strtoupper($skill['name']);
            echo '</a>';
			if(count($skills) === 1) {
				$editButton = '
				<button type="button" class="btn" data-bs-toggle="modal" data-bs-target="#editSkill" 
				data-bs-id="'. $skill['id'] .'" data-bs-name="'. $skill['name'] .'" data-bs-logo="'. $skill['logo'] .'">'.
				'<i class="fa fa-edit"></i></button>';
				$deleteButton =  '<form class="form-inline" method="post" action="index.php?page=deleteskill" ' .
				'onsubmit="return confirm(\'Do you confirm to delete ' . $skill['name'] . ' skill?\');">' .
				'<input type="hidden" name="id" value='. $skill['id'] .'>' . 
				'<button class="btn"><i class="fa fa-trash"></i></button></form>';
				echo $admin === 'true' ? $editButton : '';
				echo $admin === 'true' ? $deleteButton : '';
			}
		}
		?>
</ul>
</div>
</nav>
</div>
</div>

<div class="container">
<?php
  if (!empty($items)) {

		$idsArray = [];
		
		foreach ($items as $item) {
			if (in_array($item['id'], $idsArray)) {
				continue;
			}
			array_push($idsArray, $item['id']);
			if (!empty($item['name']) && $item['name'] !== 'IT: All') {
				$match = "/^([a-zA-Z]+:\s)(.*$)/";
				$itemName = preg_replace($match, "$2", $item['name']);
				$deleteButton = '<form class="form-inline" method="post" action="index.php?page=deleteitem" ' .
				'onsubmit="return confirm(\'Do you confirm to delete ' . $itemName. ' item?\');">' .
				'<input type="hidden" name="item_id" value='.$item['id'].'>' . 
				'<input type="hidden" name="skill_id" value='.$item['skill_id'].'>' . 
				'<button class="btn"><i class="fa fa-trash"></i></button></form>';
				$editButton = '
				<button type="button" class="btn" data-bs-toggle="modal" data-bs-target="#editItem" 
				data-bs-id="'. $item['id'].'" data-bs-name="'. $itemName.'">'.
				'<i class="fa fa-edit"></i></button>';
				echo "<div class='row mb-2, border-bottom'>";
				echo '<div class="col-4 mb-2">';
				echo $admin === true ? $editButton : '';
				echo $admin === true ? $deleteButton : '';
					if (!is_null($item['further']) && Check::isUrl($item['further'])) {
							echo '<a class="text-row" href="' . $item['further'] . '" target="_blank">' . $itemName . '</a>';
					} elseif (!is_null($item['further']) && Check::isPdf($item['further'])) {
						echo '<a class="text-row" href="./public/doc/' . $item['further'] . '" target="_blank">' . $itemName . '</a>';
					} else {
						echo '<span class="text-row">'.$itemName.'</span>';
					}
					echo '</div>';
					echo '<div class="col-7 mb-2">' . $item['description'] . '</div>';
					echo '<div class="col-1 hidden">';
						echo '<input type="hidden" id="description'.$item['id'].'" value="'.$item['description'].'">';
						echo '<input type="hidden" id="further'.$item['id'].'" value="'.$item['further'].'">';
					echo '</div>';
				echo "</div>";
			}
		}

		?>
</div>

<?php
		if (!empty($openaiResponse)) {
			?>
	<div id="openai" class="container px-4 py-5">
		<?php
		echo '<strong>OPENAI Definition:</strong><br>';
		echo '<p>'.$openaiResponse.'</p>';
		?>
	</div>
<?php
		}
		?>

<div class="container mt-4">
<?php
		$numberUrls = count($relatedUrls);
		if ($numberUrls > 0) {
			?>
<ul>
	<li>Related urls</li>
	<?php
			$idsArray = [];
			foreach ($relatedUrls as $url) {
				if (in_array($url['id'], $idsArray)) {
					continue;
				}
				array_push($idsArray, $url['id']);
				$editButton = '
				<button type="button" class="btn" data-bs-toggle="modal" data-bs-target="#editUrlModal" 
				data-bs-id="' . $url['id'] . '" data-bs-name="' . $url['name']. '" data-bs-url="' . $url['url']. '">' .
						'<i class="fa fa-edit"></i></button>';
				$deleteButton = '
				<form class="form-inline" method="post" action="index.php?page=deleteurl" ' .
				'onsubmit="return confirm(\'Do you confirm to delete ' . $url['name']. ' url?\');">' .
				'<input type="hidden" name="id" value='.$url['id'].'>' .
				'<button class="btn"><i class="fa fa-trash"></i></button></form>';
                echo '<ul><li>';
				echo $admin === true ? $editButton : '';
				echo $admin === true ? $deleteButton : '';
				echo '<a href="' . $url['url'] . '" target="_blank">' . $url['name'] . '</a>';
                echo '</li></ul>';
			}
			?>
</ul>
<?php
		}
		?>
</div>

<div class="container">
<?php
$numberDemos = count($demos);
if ($numberDemos > 0) {
?>
<ul>
    <li class="remove-bullet">Related Demos</li>
    <ul>
    <?php
        foreach ($demos as $demo) {
            echo '<li><a href="index.php?page=demo&demo_id=' . $demo['id'] . '" target="_blank">' . $demo['name'] . '</a></li>';
        }
    ?>
    </ul>
</ul>
<?php
		}
		?>
</div>
<?php
}else{
	echo '<div class="container px-4 py-5" id="featured-3">';
	echo '<h1>No data</h1>';
	echo '</div>';
}

if (!empty($entities['items'])) {
?>


<!-- Item MODAL -->
<div class="modal fade" id="editItem" tabindex="-1" aria-labelledby="editItemLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="editItemLabel">Edit Item</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form method="post" action="index.php?page=updateitem">
				  <input type="hidden" name="id" id="id" value=''>
          <div class="mb-3">
            <label for="name" class="col-form-label">Name:</label>
            <input name="name" type="text" class="form-control" id="name">
          </div>
          <div class="mb-3">
            <label for="message-text" class="col-form-label">Description:</label>
            <textarea name="description" id="description" class="form-control"></textarea>
          </div>
					<div class="mb-3">
            <label for="message-text" class="col-form-label">Further:</label>
            <textarea name="further" id="further" class="form-control"></textarea>
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
const editItem = document.getElementById('editItem')
editItem.addEventListener('show.bs.modal', event => {
  // Button that triggered the modal
  const button = event.relatedTarget
  // Extract info from data-bs-* attributes
	const id = button.getAttribute('data-bs-id')
  const name = button.getAttribute('data-bs-name')
	const url = button.getAttribute('data-bs-url')
	const title = name.substring(0,15)+'...'
	const descriptionId = 'description'+id;
	const description = document.getElementById(descriptionId).value
	const furtherId = 'further'+id;
	const further = document.getElementById(furtherId).value
  // AJAX request here and then updating in callback
  // Update the modal's content.
  const modalTitle = editItem.querySelector('.modal-title')
	const modalBodyInputId = editItem.querySelector('.modal-body #id')
	const modalBodyInputName = editItem.querySelector('.modal-body #name')
	const modalBodyInputDescription = editItem.querySelector('.modal-body #description')
	const modalBodyInputFurther = editItem.querySelector('.modal-body #further')
  modalTitle.textContent = `Edit item ${title}`
	modalBodyInputId.value = id
  modalBodyInputName.value = name
	modalBodyInputDescription.value = description
	modalBodyInputFurther.value = further
})
</script>
<!-- End Item MODAL -->

<!-- Url MODAL -->
<div class="modal fade" id="editUrlModal" tabindex="-1" aria-labelledby="editUrlLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="editUrlLabel">Edit Url</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form method="post" action="index.php?page=updateurl">
				  <input type="hidden" name="id" id="id">
          <div class="mb-3">
            <label for="name" class="col-form-label">Name:</label>
            <input name="name" type="text" class="form-control" id="name" minlength="3" required>
          </div>
          <div class="mb-3">
            <label for="message-text" class="col-form-label">Url:</label>
						<input name="url" type="text" class="form-control" id="url" minlength="12" required>
          </div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
						<button type="submit" class="btn btn-primary">Update Url</button>
					</div>
        </form>
      </div>

    </div>
  </div>
</div>
<script>
const editUrl = document.getElementById('editUrlModal')
editUrl.addEventListener('show.bs.modal', event => {
  // Button that triggered the modal
  const button = event.relatedTarget
  // Extract info from data-bs-* attributes
  const id = button.getAttribute('data-bs-id')
  const name = button.getAttribute('data-bs-name')
  const url = button.getAttribute('data-bs-url')
  const title = name.substring(0,15)+'...'
  // AJAX request here and then updating in callback
  // Update the modal's content.
  const modalTitle = editUrl.querySelector('.modal-title')
  const modalBodyInputId = editUrl.querySelector('.modal-body #id')
  const modalBodyInputName = editUrl.querySelector('.modal-body #name')
  const modalBodyInputUrl = editUrl.querySelector('.modal-body #url')
  modalTitle.textContent = `Edit Url ${title}`
  modalBodyInputId.value = id
  modalBodyInputName.value = name
  modalBodyInputUrl.value = url
})
</script>
<!-- End Url MODAL -->

<?php
}
?>
<!-- Skill MODAL -->
<div class="modal fade" id="editSkill" tabindex="-1" aria-labelledby="editSkillLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="editSkillLabel">Edit Skill</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form method="post" action="index.php?page=updateskill">
				  <input type="hidden" name="id" id="id" value=''>
          <div class="mb-3">
            <label for="name" class="col-form-label">Name:</label>
            <input name="name" type="text" class="form-control" id="name">
          </div>
          <div class="mb-3">
            <label for="message-text" class="col-form-label">logo:</label>
						<input name="logo" type="text" class="form-control" id="logo">
          </div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
						<button type="submit" class="btn btn-primary">Update Skill</button>
					</div>
        </form>
      </div>

    </div>
  </div>
</div>
<script>
const editSkill = document.getElementById('editSkill')
editSkill.addEventListener('show.bs.modal', event => {
  // Button that triggered the modal
  const button = event.relatedTarget
  // Extract info from data-bs-* attributes
	const id = button.getAttribute('data-bs-id')
  const name = button.getAttribute('data-bs-name')
	const logo = button.getAttribute('data-bs-logo')
	const title = name.substring(0,15)+'...'
  // AJAX request here and then updating in callback
  // AJAX request here and then updating in callback
  // Update the modal's content.
  const modalTitle = editSkill.querySelector('.modal-title')
	const modalBodyInputId = editSkill.querySelector('.modal-body #id')
	const modalBodyInputName = editSkill.querySelector('.modal-body #name')
	const modalBodyInputLogo = editSkill.querySelector('.modal-body #logo')
  modalTitle.textContent = `Edit Skill ${title}`
	modalBodyInputId.value = id
  modalBodyInputName.value = name
	modalBodyInputLogo.value = logo
})
</script>
<!-- End Skill MODAL -->