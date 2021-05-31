<template>
    <div class="d-flex justify-content-center col-md-12">
	<div class="card col-md-4 manage-forms">
		<div class="card-body">
			<h1 class="bg-success text-center">
			<i class="fas fa-pen-square"></i> Request <small>leave</small>
			</h1>
			<form method="post" >
				<!-- @csrf -->
				<select name="student_id">
					<!-- <option>{{ info.employee.id }}</option> -->
				</select>
				<input v-model="form" type="hidden" name="status" value="pending">
				<div class="form-group">
					<label for="reason">Reason of leave</label>
					<input v-model="form.reason" type="text" class="form-control" id="reason" name="reason" required>
				</div>
				
				<div class="form-group">
					<label for="fromDate">From</label>
					<input v-model="form.from" type="date" class="form-control" id="fromDate" name="from" required>
				</div>
				<div class="form-group">
					<label for="leaveBody">To</label>
					<input v-model="form.to" type="date" class="form-control" id="toDate" name="to" required>
				</div>
				<div class="form-btn  d-flex justify-content-center" >
					<button @click.prevent="submit" type="submit" class="btn btn-success col-md-4" name="register">Submit</button>
				</div>
			</form>
		</div>
	</div>
</div>
</template>

<script>
export default {
    data() {
		return{
		info: '',
		form:{
			reason:'',
			from:'',
			to:''
			
		}
		}
	},
	// // mounted(){
    //         this.getInfo();
    //     },
	created()
	{
		axios.defaults.headers.common['Authorization']= "Bearer " + localStorage.getItem("access_token");
	},
	methods:
	{
		submit()
		{
			axios.post('/api/request-leave', this.form ).then(({data})=>{
				
			})
		}
		// getInfo()
		// {
		// 	axios.get('/api/get-info').then(({ data }) => {
		// 		this.info = data;
		// 		console.log(data);
		// 	})
		// }
	}
}
</script>