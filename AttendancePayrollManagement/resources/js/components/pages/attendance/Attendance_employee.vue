<template>
    <v-app id="index">
        <v-app-bar app>
            <!-- DESKTOP VIEW -->
            <v-toolbar
                class="hidden-xs-only"
                color="transparent"
                flat
            >
                <v-dialog v-model="dialogAttendance1" persistent max-width="600px">
                    <template v-slot:activator="{ on, attrs }">
                        <v-btn
                            dark
                            v-bind="attrs"
                            v-on="on"
                            @click="updateTime"
                        >
                            Attendance
                        </v-btn>
                    </template>
                    <v-card id="attendance">
                        <v-card-title>
                            <span class="headline"> ATTENDANCE </span>
                            <v-spacer></v-spacer>
                            <v-btn
                                dark
                                @click="close"
                                style="padding:4px;height:24px;min-width:4px;margin-left:5px;"
                                text
                            >
                                <v-icon> mdi-close </v-icon>
                            </v-btn>
                        </v-card-title>
                        <v-card-subtitle class="text-center">
                            <p class="date"> {{ this.date }} </p>
                            <p class="time"> {{ this.time }} </p>
                        </v-card-subtitle>
                        <v-card-text class="text-center">
                            <v-container>
                                <v-row>
                                    <v-col cols="12" md="12">
                                        <v-text-field
                                            class="attend"
                                            label="ENTER EMPLOYEE ID"
                                            v-model="attendance.employee_id"
                                            outlined
                                        ></v-text-field>
                                        <p class="text">YOU CAN'T MAKE UP FOR LOST TIME.</p>
                                        <p class="text">YOU CAN ONLY DO BETTER IN THE FUTURE.</p>
                                    </v-col>
                                </v-row>
                            </v-container>
                        </v-card-text>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn dark text @click.prevent="_log">Log</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
                <v-spacer></v-spacer>
                <v-btn
                    dark
                    @click="dialogLogin = true"
                >
                    Login
                </v-btn>
                <v-btn
                    dark
                    @click="dialogRegister = true"
                >
                    Register
                </v-btn>
            </v-toolbar>

            <!-- MOBILE VIEW -->
            <v-toolbar
                class="hidden-sm-and-up"
                color="transparent"
                flat
            >
                <v-dialog v-model="dialogAttendance2" persistent max-width="600px">
                    <template v-slot:activator="{ on, attrs }">
                        <v-btn
                            dark
                            v-bind="attrs"
                            v-on="on"
                            @click="updateTime"
                            style="padding:4px;height:24px;min-width:4px;margin-left:5px;"
                        >
                            <v-icon> mdi-account-check </v-icon>
                        </v-btn>
                    </template>
                    <v-card id="attendance">
                        <v-card-title>
                            <span class="headline">ATTENDANCE</span>
                            <v-spacer></v-spacer>
                            <v-btn
                                dark
                                @click="close"
                                style="padding:4px;height:24px;min-width:4px;margin-left:5px;"
                                text
                            >
                                <v-icon> mdi-close </v-icon>
                            </v-btn>
                        </v-card-title>
                        <v-card-subtitle class="text-center">
                            <p class="date"> {{ this.date }} </p>
                            <p class="time"> {{ this.time }} </p>
                        </v-card-subtitle>
                        <v-card-text class="text-center">
                            <v-container>
                                <v-row>
                                    <v-col cols="12" md="12">
                                        <v-text-field
                                            class="attend"
                                            label="ENTER EMPLOYEE ID"
                                            v-model="attendance.employee_id"
                                            outlined
                                        ></v-text-field>
                                        <p class="text">YOU CAN'T MAKE UP FOR LOST TIME.</p>
                                        <p class="text">YOU CAN ONLY DO BETTER IN THE FUTURE.</p>
                                    </v-col>
                                </v-row>
                            </v-container>
                        </v-card-text>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn dark text @click.prevent="_log">Log</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
                <v-spacer></v-spacer>
                <v-btn
                    dark
                    @click="dialogLogin = true"
                    style="padding:4px;height:24px;min-width:4px;margin-left:5px;"
                >
                    <v-icon> mdi-login </v-icon>
                </v-btn>
                <v-btn
                    dark
                    @click="dialogRegister = true"
                    style="padding:4px;height:24px;min-width:4px;margin-left:5px;"
                >
                    <v-icon> mdi-account-plus </v-icon>
                </v-btn>
            </v-toolbar>
        </v-app-bar>

        <!-- LOGIN DIALOG -->
        <validation-observer v-slot="{ handleSubmit }">
            <v-dialog v-model="dialogLogin" max-width="500px">
                <v-card flat id="login">
                    <v-container>
                        <v-card-title>
                            Login
                            <v-spacer></v-spacer>
                            <v-btn
                                dark
                                @click="close"
                                style="padding:4px;height:24px;min-width:4px;margin-left:5px;"
                                text
                            >
                                <v-icon> mdi-close </v-icon>
                            </v-btn>
                        </v-card-title>
                        <v-divider></v-divider>
                        <v-form @submit.prevent="handleSubmit(login)">
                            <v-row>
                                <v-col
                                    cols="12"
                                    md="12"
                                >
                                    <validation-provider v-slot="{ errors }" name="Email" rules="required|email">
                                    <v-text-field
                                        v-model="user.email"
                                        label="Email"
                                        type="email"
                                        :error-messages="errors"
                                    >
                                        <v-icon
                                            slot="prepend"
                                        >
                                            mdi-email
                                        </v-icon>
                                    </v-text-field>
                                    </validation-provider>
                                </v-col>
                            </v-row>
                            <v-row>
                                <v-col
                                    cols="12"
                                    md="12"
                                >
                                    <validation-provider v-slot="{ errors }" name="Password" rules="required">
                                    <v-text-field
                                        v-model="user.password"
                                        label="Password"
                                        :type="showPassword ? 'text' : 'password'"
                                        :error-messages="errors"
                                        :append-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'"
                                        @click:append="showPassword = !showPassword"
                                    >
                                        <v-icon
                                            slot="prepend"
                                        >
                                            mdi-lock
                                        </v-icon>
                                    </v-text-field>
                                    </validation-provider>
                                </v-col>
                            </v-row>
                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn type="submit" > Submit </v-btn>
                            </v-card-actions>
                        </v-form>
                    </v-container>
                </v-card>
            </v-dialog>
        </validation-observer>

        <!-- REGISTER DIALOG -->
        <validation-observer v-slot="{ handleSubmit }">
            <v-dialog v-model="dialogRegister" max-width="500px">
                <p class="alert alert-danger" v-if="errors.length">
                    <b>Please correct the following error(s):</b>
                    <ul class="mb-0">
                        <li v-for="(error, index) in errors" :key="index">{{ error }}</li>
                    </ul>
                </p>
                <v-card flat id="register">
                    <v-container>
                        <v-card-title>
                            Register
                            <v-spacer></v-spacer>
                            <v-btn
                                dark
                                @click="close"
                                style="padding:4px;height:24px;min-width:4px;margin-left:5px;"
                                text
                            >
                                <v-icon> mdi-close </v-icon>
                            </v-btn>
                        </v-card-title>
                        <v-divider></v-divider>
                        <v-form @submit.prevent="handleSubmit(register)">
                            <v-row>
                                <v-col
                                    cols="12"
                                    md="12"
                                >
                                    <validation-provider v-slot="{ errors }" name="Name" rules="required|alpha_spaces">
                                    <v-text-field
                                        v-model="user.name"
                                        label="Username"
                                        :error-messages="errors"
                                    >
                                        <v-icon
                                            slot="prepend"
                                        >
                                            mdi-account
                                        </v-icon>
                                    </v-text-field>
                                    </validation-provider>
                                </v-col>
                                <v-col
                                    cols="12"
                                    md="12"
                                >
                                    <validation-provider v-slot="{ errors }" name="Email" rules="required|email">
                                    <v-text-field
                                        v-model="user.email"
                                        label="Email"
                                        type="email"
                                        :error-messages="errors"
                                    >
                                        <v-icon
                                            slot="prepend"
                                        >
                                            mdi-email
                                        </v-icon>
                                    </v-text-field>
                                    </validation-provider>
                                </v-col>
                                <v-col
                                    cols="12"
                                    md="12"
                                >
                                    <validation-provider v-slot="{ errors }" name="Password" rules="required|confirmed:confirm" vid="password">
                                    <v-text-field
                                        v-model="user.password"
                                        label="Password"
                                        :type="showPassword ? 'text' : 'password'"
                                        :error-messages="errors"
                                        :append-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'"
                                        @click:append="showPassword = !showPassword"
                                    >
                                        <v-icon
                                            slot="prepend"
                                        >
                                            mdi-lock
                                        </v-icon>
                                    </v-text-field>
                                    </validation-provider>
                                </v-col>
                                <v-col
                                    cols="12"
                                    md="12"
                                >
                                    <validation-provider v-slot="{ errors }" name="Confirm Password" rules="required|confirmed:password" vid="confirm">
                                    <v-text-field
                                        v-model="user.password_confirmation"
                                        label="Confirm Password"
                                        :type="showConfirmPassword ? 'text' : 'password'"
                                        :error-messages="errors"
                                        :append-icon="showConfirmPassword ? 'mdi-eye' : 'mdi-eye-off'"
                                        @click:append="showConfirmPassword = !showConfirmPassword"
                                    >
                                        <v-icon
                                            slot="prepend"
                                        >
                                            mdi-lock
                                        </v-icon>
                                    </v-text-field>
                                    </validation-provider>
                                </v-col>
                            </v-row>
                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn type="submit" > Submit </v-btn>
                            </v-card-actions>
                        </v-form>
                    </v-container>
                </v-card>
            </v-dialog>
        </validation-observer>

        <!-- <video-bg
            img="../assets/Eclipse.jpg">
        </video-bg> -->
    </v-app>
</template>

<script>
    var week = ['SUN', 'MON', 'TUE', 'WED', 'THU', 'FRI', 'SAT'];
    import { ValidationObserver, ValidationProvider, extend, setInteractionMode } from 'vee-validate';
  	import { required, email, alpha_spaces, confirmed } from 'vee-validate/dist/rules'

	   setInteractionMode('eager')

		extend('required', {
			...required,
			message: '{_field_} can not be empty'
		})

		extend('email', {
			...email,
			message: 'Email must be valid',
		})

        extend('alpha_spaces', {
			...alpha_spaces,
			message: '{_field_} may only contain alphabetic characters as well as spaces'
		})

		extend('confirmed', {
			...confirmed,
			message: '{_field_} does not match'
		})

    export default {
        components: { ValidationObserver, ValidationProvider },
        data(){
            return {
                dialogAttendance1: false,
                dialogAttendance2: false,
                attendance: {},
                date:'',
                time:'',

                dialogLogin: false,
                dialogRegister: false,

                user: {},
                errors: [],
                showPassword: false,
                showConfirmPassword: false,
            }
        },

        methods: {
            close() {
                this.dialogAttendance1 = false;
                this.dialogAttendance2 = false;
                this.dialogLogin = false;
                this.dialogRegister = false;
            },
            login() {
				this.$store.dispatch('currentUser/loginUser', this.user);
			},
            register() {
				const data =
                {
                    first_name:		this.user.name,
                    email:		this.user.email,
                    password:	this.user.password
                }
				axios.post('/api/user/register', data)
					.then((response) =>
					{
						console.log(response.data);
						if(response.data.access_token)
						{
							localStorage.setItem('access_token', response.data.access_token);
						}
						window.location.replace('/user');
					})
                    .catch(error =>{
                        this.errors.push(error.response.data.error);
                    })
			},
            _log(){
                axios.post('/api/attendance', this.attendance)
                    .then(({ data }) => {
                        (data.message != null) ? alert(data.message) : alert('You have no logs in attendance today. Conatct your HR')
                })
            },
            updateTime() {
                var cd = new Date();
                this.time = this.zeroPadding(cd.getHours(), 2) + ':' + this.zeroPadding(cd.getMinutes(), 2) + ':' + this.zeroPadding(cd.getSeconds(), 2);
                this.date = this.zeroPadding(cd.getFullYear(), 4) + '-' + this.zeroPadding(cd.getMonth()+1, 2) + '-' + this.zeroPadding(cd.getDate(), 2) + ' ' + week[cd.getDay()];
                setInterval(this.updateTime, 1000)
            },
            zeroPadding(num, digit) {
                var zero = '';
                for(var i = 0; i < digit; i++) {
                    zero += '0';
                }
                return (zero + num).slice(-digit);
            }
        }
    }
</script>
