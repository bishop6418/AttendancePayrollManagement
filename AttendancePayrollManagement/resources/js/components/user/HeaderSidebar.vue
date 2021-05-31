<template>
	<div>
	<v-app-bar
		style="box-shadow:none"
		clipped-left
		app
	>
		<!-- DESKTOP VIEW -->
		<v-toolbar
			class="hidden-xs-only"
			color="transparent"
			flat
		>
			<v-toolbar-title
				class="tile"
			>
				<v-img src="../assets/PM_Logo.png"/>
			</v-toolbar-title >

			<v-spacer></v-spacer>

			<v-menu offset-y>
				<template v-slot:activator="{ on }">
					<v-card width="auto" flat color="transparent" v-on="on">
						<v-list-item>
							<v-list-item-avatar>
								<img :src="show_Image" alt="John">
							</v-list-item-avatar>
							<v-list-item-content>
								<v-list-item-title class="title">
								{{currentUser.name}}
								</v-list-item-title>
							</v-list-item-content>
							<v-list-item-action>
								<v-icon>mdi-menu-down</v-icon>
							</v-list-item-action>
						</v-list-item>
					</v-card>
				</template>
				<v-list>
					<v-list-item link @click="dialogProfile = true">
						<v-list-item-title> Profile </v-list-item-title>
					</v-list-item>
					<v-list-item link>
						<v-list-item-title> Configuration </v-list-item-title>
					</v-list-item>
					<v-list-item link>
						<v-list-item-title> Change Password </v-list-item-title>
					</v-list-item>
					<v-list-item @click.prevent="logout">
						<v-list-item-title> Logout </v-list-item-title>
					</v-list-item>
				</v-list>
			</v-menu>
		</v-toolbar>

		<!-- MOBILE VIEW -->
		<v-toolbar
			class="hidden-sm-and-up"
			color="transparent"
			flat
		>
			<v-app-bar-nav-icon @click="drawer=true"></v-app-bar-nav-icon>
			<v-toolbar-title
				class="tile"
			>
				<router-link to="/" exact>
					<v-img src="../assets/hemlogo.png"/>
				</router-link>
			</v-toolbar-title>

			<v-spacer></v-spacer>

			<v-menu offset-y>
				<template v-slot:activator="{ on }">
					<v-card width="auto" flat color="transparent" v-on="on">
						<v-list-item link>
							<v-list-item-avatar title="John Leider">
								<img :src="show_Image" alt="John">
							</v-list-item-avatar>
							<v-icon>mdi-menu-down</v-icon>
						</v-list-item>
					</v-card>
				</template>
				<v-list>
					<v-list-item link @click="dialogProfile = true">
						<v-list-item-title> Profile </v-list-item-title>
					</v-list-item>
					<v-list-item link>
						<v-list-item-title> Configuration </v-list-item-title>
					</v-list-item>
					<v-list-item link>
						<v-list-item-title> Change Password </v-list-item-title>
					</v-list-item>
					<v-list-item @click.prevent="logout">
						<v-list-item-title> Logout </v-list-item-title>
					</v-list-item>
				</v-list>
			</v-menu>
		</v-toolbar>
  	</v-app-bar>

	<v-navigation-drawer
		:permanent="$vuetify.breakpoint.smAndUp"
		v-model="drawer"
		clipped
		app
	>
		<v-list
			dense
			nav
		>
			<v-list-item
				v-for="item in items"
				:key="item.title"
				:to="item.to"
				link
			>
				<v-list-item-icon>
					<v-icon>{{ item.icon }}</v-icon>
				</v-list-item-icon>

				<v-list-item-content>
					<v-list-item-title>
						{{ item.title }}
					</v-list-item-title>
				</v-list-item-content>
			</v-list-item>
		</v-list>
	</v-navigation-drawer>

	<!-- PROFILE DIALOG -->
	<v-dialog v-model="dialogProfile" max-width="500px">
		<v-card>
			<v-card-title class="headline justify-center">PROFILE</v-card-title>
			<v-card-text>
				<v-container>
				<v-row justify="center">
					<v-col cols="12" sm="6" md="7">
					<div class="form-group">
						<input type="file" class="form-control">
					</div>
					</v-col>
				</v-row>
				</v-container>
			</v-card-text>
			<v-card-actions>
				<v-spacer></v-spacer>
				<v-btn color="blue darken-1" text>Save</v-btn>
				<v-btn color="blue darken-1" text>Cancel</v-btn>
				<v-spacer></v-spacer>
			</v-card-actions>
		</v-card>
	</v-dialog>
</div>
</template>
<script>
import axios from 'axios';
export default {
	data() {
			return {

				// user: null,
                user_profile:'',
				dialogProfile: false,
				drawer: false,
				items: [
					{ title: 'Dashboard',	icon: 'mdi-view-dashboard-outline',			to: '/user/dashboard' },
					// { title: 'Employees', 	icon: 'mdi-account-group-outline',			to: '/admin/employee',
					// 	children: [
					// 		{ title: 'User', icon: 'mdi-account-box-multiple-outline', 	to: '/admin/user' }
					// 	]
					// },
					{ title: 'Attendance', 	icon: 'mdi-account-check-outline', 			to: '/user/attendance' },
					{ title: 'Payroll', 	icon: 'mdi-file-document-outline', 			to: '/user/mypayroll'},
					// { title: 'Users', 		icon: 'mdi-account-box-multiple-outline', 	to: '/admin/user'},
					// { title: 'Expenses', 	icon: 'mdi-calculator', 					to: '/admin/expense'},
					// { title: 'Position', 	icon: 'mdi-chair-rolling', 					to: '/admin/position'},
					// { title: 'Benefit', 	icon: 'mdi-shield-check', 					to: '/admin/benefit'},
					// { title: 'Schedule', 	icon: 'mdi-clock-outline', 					to: '/admin/schedule'},
					// lloyd
					// { title: 'Roles and Permissions', 	icon: 'mdi-tune', 		to: '/admin/roles-and-permission'}
				]
			}
		},

		created(){
				if(localStorage.hasOwnProperty('access_token')){
					axios.defaults.headers.common['Authorization']= "Bearer " + localStorage.getItem("access_token");
					this.$store.dispatch('currentUser/getUser');
				}
				else{
					window.location.replace('/');
				}

			},
        mounted(){
            this.curr_user_profile()
        },
	computed:{
			currentUser:{
				get(){
				return this.$store.state.currentUser.user;
				}
			},
            show_Image(){
            if(this.user_profile != null){
                return this.user_profile
            }
            return '/assets/blank.png'
        }
		},
	methods:{
		logout()
		{
			this.$store.dispatch('currentUser/logoutUser');
		},
        curr_user_profile(){
            axios.get('api/get-info')
            .then(({data})=>{
                this.user_profile = data.employee.image_path
            });
        }
	}
}
</script>
