<template>
<div class="container d-flex justify-content-center  mt-2">
	<div class="card">
		<div class="card-body">
			<h1 class="bg-warning text-center">
			<v-icon>mdi-alert-circle-outline</v-icon> Your <small>Info</small>
			</h1>
			<table class="table">
				<tbody>
					<tr>
						<td rowspan="2">
							<img :src="show_Image" alt="John" width="150px" height="150px" class="rounded-circle">
                            </td>
						<th>User Id:</th>
						<td>{{info.employee.id}}</td>
						<th>Email:</th>
						<td>{{info.employee.email}}</td>
					</tr>
					<tr>
						<th>First name:</th>
						<td>{{info.employee.first_name}}</td>
						<th>Last name:</th>
						<td>{{info.employee.last_name}}</td>

					</tr>
					<tr>
						<td rowspan="2">
                            </td>
						<th>Position:</th>
						<td>{{info.position.name}}</td>
						<th>Date hired:</th>
						<td>{{info.employee.date_hired}}</td>
					</tr>
					<tr>
						<th>Benefit:</th>
						<td>{{info.benefit_name}}</td>
						<th>Reference number:</th>
						<td>{{info.ref_number}}</td>

					</tr>

					<tr>
						<td colspan="4" class="text-center">
							<router-link to="/user/mypayroll" class="btn btn-warning">My Payroll</router-link>
							<router-link to="/user/attendance" class="btn btn-dark">view Attendance</router-link>
							<router-link to="/user/myLeaves" class="btn btn-warning">Request Leave</router-link>
						</td>
                    </tr>
					</tbody>
				</table>
	</div>
        </div>

</div>
</template>

<script>
export default {
	data() {
		return{
		info: [
			{
				employee:''
			}
		],
        image_path:''
		}

	},
	mounted(){
            this.getInfo();
        },
    computed:{
        show_Image(){
            if(this.image_path != null){
                return this.image_path
            }
            return '/assets/blank.png'
        }
    },

	methods:
	{
		getInfo()
		{
			axios.get('/api/get-info').then(({ data }) => {
				this.info = data;
                this.image_path = this.info.employee.image_path
			})
		},
	}
    // created(){
	// 			if(localStorage.hasOwnProperty('access_token')){
	// 				axios.defaults.headers.common['Authorization']= "Bearer " + localStorage.getItem("access_token");
	// 				this.$store.dispatch('currentUser/getUser');
	// 			}
	// 			else{
	// 				window.location.replace('/');
	// 			}

	// 		},
	//  computed:{

	// 		currentUser:{
	// 			get(){
	// 			return this.$store.state.currentUser.user;
	// 			}
	// 		}
	// 	},
}
</script>
