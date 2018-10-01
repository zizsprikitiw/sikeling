<?php include('header.php'); ?>
<?php include('session.php'); ?>
<?php include('navbar_member.php'); ?>
    <div class="container">
		<div class="margin-top">
			<div class="row">	
			<div class="span12">	
		
             <div class="alert alert-info">Add Member</div>
			<p><a href="member.php" class="btn btn-info"><i class="icon-arrow-left icon-large"></i>&nbsp;Back</a></p>
	<div class="addstudent">
	<div class="details">Please Enter Details Below</div>		
	<form class="form-horizontal" method="POST" action="member_save.php" enctype="multipart/form-data">
			
		<div class="control-group">
			<label class="control-label" for="inputEmail">Firstname:</label>
			<div class="controls">
			<input type="text" id="inputEmail" name="firstname"  placeholder="Firstname" required>

			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="inputPassword">Lastname:</label>
			<div class="controls">
			<input type="text" id="inputPassword" name="lastname"  placeholder="Lastname" required>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="inputPassword">Gender:</label>
			<div class="controls">
			<select name="gender" required>
				<option></option>
				<option>Male</option>
				<option>Female</option>
			</select>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="inputPassword">Adddress:</label>
			<div class="controls">
			<input type="text" id="inputPassword" name="address"  placeholder="Address" required>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="inputPassword">Cellphone Number:</label>
			<div class="controls">
			<input type='tel' pattern="[0-9]{12,12}" class="search" name="contact"  placeholder="Phone Number"  autocomplete="off"  maxlength="12" >
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="inputPassword">Type:</label>
			<div class="controls">
			<select name="type" required>
			
			
			
									<option></option>
									<option>Lapan - Kapus Tekbang</option>
									<option>Lapan - Profas</option>
									<option>Lapan - Diseminasi</option>
									<option>Lapan - Kepegawaian</option>
									<option>Lapan - PTT/Outsourching</option>									
									<option>Teacher</option>
									<option>Student</option>
									<option></option>
									
				</select>
			</div>
		</div>
			
		<div class="control-group">
			<label class="control-label" for="inputPassword">Year Level:</label>
			<div class="controls">
				<select name="year_level" >
					

									<option> </option>
									<option>SMA/SMU/Sederajat</option>
									<option>Diploma</option>
									<option>Bachelor Degree</option>
									<option>Magister</option>
									<option>Doctor</option>
				</select>
			</div>
		</div>
		
		
				
		
		
		
		<div class="control-group">
			<div class="controls">
			<button name="submit" type="submit" class="btn btn-success"><i class="icon-save icon-large"></i>&nbsp;Save</button>
			</div>
		</div>
    </form>					
			</div>		
			</div>		
			</div>
		</div>
    </div>
<?php include('footer.php') ?>