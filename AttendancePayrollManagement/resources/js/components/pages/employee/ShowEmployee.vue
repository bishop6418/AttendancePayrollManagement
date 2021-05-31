<template>
    <v-card flat id="showEmp">
        <v-card-title class="text-uppercase">
            {{ employee.first_name }}
            {{ employee.last_name }}
            :: Profile
            <v-spacer></v-spacer>
            <v-btn
                small
                @click.prevent="goto"
            >
                <v-icon left>mdi-arrow-left-bold</v-icon>BACK
            </v-btn>
            <v-btn
                small
                @click.prevent="editEmployee"
            > EDIT </v-btn>
        </v-card-title>
        <v-divider></v-divider>
            <v-container>
                <v-row>
                    <v-col
                        cols="12"
                        md="3"
                        class="justify-center text-center"
                    >
                        <v-img
                            class="_profile"
                            :src="show_Image"
                            aspect-ratio="1.7"
                            contain
                            @click="img_dialog = true"
                        ></v-img>
                        <p class="font-italic"> click image to change </p>
                        <v-dialog
                            v-model="img_dialog"
                            max-width="1000px"
                        >
                        <v-card>
                            <form action="">
                                <v-img :src="show_Image" aspect-ratio="2" contain></v-img>
                                <input
                                type="file"
                                @change="filePicked"
                                ref="fileInput"
                                style="display:none;"
                                accept="image/*"
                                >
                                <v-btn
                                    small
                                    absolute
                                    bottom
                                    left
                                    @click="pickedOnfile"
                                >
                                    UPLOAD PHOTO
                                </v-btn>
                                <v-btn
                                    small
                                    absolute
                                    bottom
                                    right
                                    @click="save"
                                >
                                    SAVE
                                </v-btn>
                            </form>
                        </v-card>
                        </v-dialog>
                    </v-col>
                    <v-col
                        cols="12"
                        md="4"
                    >
                        <v-card-text>
                            <p class="font-weight-black"> General Information </p>
                            <p class="font-weight-regular"> Full Name:      {{ employee.first_name }} {{ employee.last_name }} </p>
                            <p class="font-weight-regular"> Age:            {{ employee.age }} </p>
                            <p class="font-weight-regular"> Birthdate:      {{ moment(employee.birthdate).format("MMMM D, YYYY") }} </p>
                            <p class="font-weight-regular"> Gender:         {{ employee.gender }} </p>
                            <p class="font-weight-regular"> Marital Status: {{ employee.marital_status }} </p>
                            <p class="font-weight-regular"> Address:        {{ employee.address }} </p>
                        </v-card-text>
                        <v-card-text>
                            <p class="font-weight-black"> Company Information </p>
                            <p class="font-weight-regular"> Position:       {{ employee.position.name }} </p>
                            <p class="font-weight-regular"> Date Hired:     {{ moment(employee.date_hired).format("MMMM D, YYYY") }} </p>
                            <p class="font-weight-regular"> Schedule:       {{ employee.schedule.shift }} </p>
                            <p class="font-weight-regular"> Leave Credits:  {{ employee.leave_credits }} </p>
                        </v-card-text>
                    </v-col>
                    <v-col
                        cols="12"
                        md="5"
                    >
                        <v-card-text>
                            <p class="font-weight-black"> Contact Information </p>
                            <p class="font-weight-regular"> E-mail:         {{ employee.email }} </p>
                            <p class="font-weight-regular"> Contact Number: {{ employee.contact_number }} </p>
                        </v-card-text>
                        <v-card-text>
                            <p class="font-weight-black"> Financial Information </p>
                            <p class="font-weight-regular"> Rate Type:      {{ employee.rate.rate_type }} </p>
                            <p class="font-weight-regular"> Basic Salary:   {{ employee.rate.basic_salary }} </p>
                            <p class="font-weight-regular"> Additional Salary: {{ employee.rate.additional }} </p>
                            <p class="font-weight-regular"> Total Salary:   {{ Number(employee.rate.basic_salary) + Number(employee.rate.additional) }} </p>
                        </v-card-text>
                        <v-card-text>
                            <p class="font-weight-black"> Benefits Information </p>
                            <p class="font-weight-regular" v-for="benefit in employee.benefits"> {{ benefit.name }}: {{ benefit.pivot.ref_number }} </p>
                        </v-card-text>
                    </v-col>
                </v-row>
            </v-container>
    </v-card>
</template>

<script>
	export default {
		data() {
			return {
                img_dialog: false,
                image_path:'',
                employee_id: {},
                employee: {
					benefits: [
                        {
                            pivot: {}
                        }
                    ],
                    position: {},
                    schedule: {},
                    rate: {},
				},
			}
		},
        computed:{
        show_Image(){
            if(this.image_path != null){
                return this.image_path
            }
            return '/assets/blank.png'
        }
        },
        created() {
            this.employee_id = this.$route.params.employee_id;
        },
		mounted() {
			this.getDetails()
		},
		methods: {
            pickedOnfile(){
                this.$refs.fileInput.click()
            },
            filePicked(event){
                const image = event.target.files[0];
                const reader = new FileReader();
                reader.readAsDataURL(image);
                reader.onload = e =>{
                    this.image_path = e.target.result;
                };
            },
            save(){
                const update = {
                    emp_id: this.employee_id,
                    path: this.image_path,
                }
                axios.post('/api/upload-image', update)
                .then(({ data }) => {
                    alert('Profile Picture Successfully Updated')
                })
            },
            goto() {
                this.$router.push({ name: "Employee" });
            },
			getDetails() {
				axios.get('/api/get-employee/' + this.employee_id)
                .then(({ data }) => {
          			this.employee = data;
                    this.image_path = this.employee.image_path
        		})
			},
            editEmployee() {
				this.$router.push({ name: "Edit-Employee", params: { employee_id: this.employee_id } });
            },
		}
	}
</script>

<style lang="scss" scoped>
._profile{
cursor: pointer;
}
</style>>

