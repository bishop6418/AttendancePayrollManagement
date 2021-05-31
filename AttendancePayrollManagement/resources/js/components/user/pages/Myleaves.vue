<template>
  <v-data-table
    :headers="headers"
    :items="leaves.leave"
    sort-by="calories"
    class="elevation-1"
  >
    <template v-slot:top>
      <v-toolbar
        flat
      >
        <v-toolbar-title>My leave credits</v-toolbar-title>
        <v-divider
          class="mx-4"
          inset
          vertical
        ></v-divider>
        <p>{{leaves.employee.leave_credits}} days</p>
        <v-spacer></v-spacer>
        <v-dialog
          v-model="dialog"
          max-width="500px"
        >
          <template v-slot:activator="{ on, attrs }">
            <v-btn
              color="primary"
              dark
              class="mb-2"
              v-bind="attrs"
              v-on="on"
            >
              Request Leave
            </v-btn>
          </template>
          <v-card>
            <v-card-title>
              <span class="headline">{{ formTitle }}</span>
            </v-card-title>

            <v-card-text>
              <v-container>
                <v-row>
                  <v-col
                    cols="12"
                    sm="6"
                    md="4"
                  >
                    <v-text-field
                      v-model="editedItem.description"
                      label="Reason"
                    ></v-text-field>
                  </v-col>
                    <v-col
                      cols="12"
                      sm="6"
                      md="4"
                    >
                      <v-menu
                        ref="menu"
                        v-model="menu"
                        :close-on-content-click="false"
                        :return-value.sync="date"
                        transition="scale-transition"
                        offset-y
                        min-width="auto"
                      >
                        <template v-slot:activator="{ on, attrs }">
                          <v-text-field
                            v-model="editedItem.from"
                            label="Start Date"
                            prepend-icon="mdi-calendar"
                            readonly
                            v-bind="attrs"
                            v-on="on"
                          ></v-text-field>
                        </template>
                        <v-date-picker
                          v-model="editedItem.from"
                          no-title
                          scrollable
                        >
                          <v-spacer></v-spacer>
                          <v-btn
                            text
                            color="primary"
                            @click="menu = false"
                          >
                            Cancel
                          </v-btn>
                          <v-btn
                            text
                            color="primary"
                            @click="$refs.menu.save(date)"
                          >
                            OK
                          </v-btn>
                        </v-date-picker>
                      </v-menu>
                    </v-col>
                    <v-col
                      cols="12"
                      sm="6"
                      md="4"
                    >
                      <v-menu
                        ref="menu2"
                        v-model="menu2"
                        :close-on-content-click="false"
                        :return-value.sync="date"
                        transition="scale-transition"
                        offset-y
                        min-width="auto"
                      >
                        <template v-slot:activator="{ on, attrs }">
                          <v-text-field
                            v-model="editedItem.to"
                            label="End Date"
                            prepend-icon="mdi-calendar"
                            readonly
                            v-bind="attrs"
                            v-on="on"
                          ></v-text-field>
                        </template>
                        <v-date-picker
                          v-model="editedItem.to"
                          no-title
                          scrollable
                        >
                          <v-spacer></v-spacer>
                          <v-btn
                            text
                            color="primary"
                            @click="menu2 = false"
                          >
                            Cancel
                          </v-btn>
                          <v-btn
                            text
                            color="primary"
                            @click="$refs.menu2.save(date)"
                          >
                            OK
                          </v-btn>
                        </v-date-picker>
                      </v-menu>
                    </v-col>
                  <!-- <v-col
                    cols="12"
                    sm="6"
                    md="4"
                  >
                    <v-text-field
                      v-model="editedItem.from"
                      label="From"
                    ></v-text-field>
                  </v-col> -->
                  <!-- <v-col
                    cols="12"
                    sm="6"
                    md="4"
                  >
                    <v-text-field
                      v-model="editedItem.to"
                      label="To"
                    ></v-text-field>
                  </v-col> -->
                </v-row>
              </v-container>
            </v-card-text>

            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn
                color="blue darken-1"
                text
                @click="close"
              >
                Cancel
              </v-btn>
              <v-btn
                color="blue darken-1"
                text
                @click="save"
              >
                Save
              </v-btn>
            </v-card-actions>
          </v-card>
        </v-dialog>
        <v-dialog v-model="dialogDelete" max-width="500px">
          <v-card>
            <v-card-title class="headline">Are you sure you want to delete this request?</v-card-title>
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="blue darken-1" text @click="closeDelete">Cancel</v-btn>
              <v-btn color="blue darken-1" text @click="deleteItemConfirm">OK</v-btn>
              <v-spacer></v-spacer>
            </v-card-actions>
          </v-card>
        </v-dialog>
      </v-toolbar>
    </template>
    <template v-slot:item.actions="{ item }">
      <!-- <v-icon
        small
        class="mr-2"
        @click="editItem(item)"
      >
        mdi-pencil
      </v-icon> -->
      <!-- <v-icon
        small
        @click="deleteItem(item)"
      >
        mdi-delete
      </v-icon> -->
    </template>
    <!-- <template v-slot:no-data>
      <v-btn
        color="primary"
        @click="initialize"
      >
        Reset
      </v-btn>
    </template> -->
  </v-data-table>
</template>
<script>
  export default {
    data: () => ({
      dialog: false,
      dialogDelete: false,
        headers:
                [
                    { text: 'ID',           value: 'id', align: 'start' },
                    { text: 'REASON',   value: 'description' },
					          { text: 'FROM',    value: 'from' },
                    { text: 'TO',		value: 'to' },
                    { text: 'NUMBER OF DAYS',	value: 'no_of_days' },
                    { text: 'STATUS',     value: 'status' },
                    { text: 'LEAVE APPLIED',     value: 'created_at' },
                    // { text: 'ACTIONS',      value: 'actions', sortable: false }
                ],
      leaves: [],
      leave_credits:[],
      editedIndex: -1,
      editedItem: {
        description: '',
        from:'',
        to: '',
      },
      defaultItem: {
        description: '',
        from:'',
        to: '',
      },
       date: new Date().toISOString().substr(0, 10),
      //  date2: new Date().toISOString().substr(0, 10),
       menu: false,
       menu2: false,
    }),

    mounted()
    {
        this.getLeaves();
    },

    computed: {
      formTitle () {
        return this.editedIndex === -1 ? 'Request Leave' : 'Edit Leave'
      },
    },

    watch: {
      dialog (val) {
        val || this.close()
      },
      dialogDelete (val) {
        val || this.closeDelete()
      },
    },

    created () {
      this.initialize()
    },

    methods: {
      initialize () {
        this.leaves = [];
      },

    getLeaves()
    {
        axios.get('/api/get-leaves').then(({ data }) => {
          this.leaves = data;
          console.log(leaves);
        })
    },
      editItem (item) {
        this.editedIndex = this.leaves.indexOf(item)
        this.editedItem = Object.assign({}, item)
        this.dialog = true
      },

      deleteItem (item) {
        this.editedIndex = this.leaves.indexOf(item)
        this.editedItem = Object.assign({}, item)
        this.dialogDelete = true
      },

      deleteItemConfirm () {
            axios.delete(`/api/delete-leave/${this.editedItem.id}`)
            .then(function (response) {
              console.log(response);
              alert('successfully deleted');
            })  
        this.leaves.splice(this.editedIndex, 1)
        this.closeDelete()
      },

      close () {
        this.dialog = false
        this.$nextTick(() => {
          this.editedItem = Object.assign({}, this.defaultItem)
          this.editedIndex = -1
        })
      },

      closeDelete () {
        this.dialogDelete = false
        this.$nextTick(() => {
          this.editedItem = Object.assign({}, this.defaultItem)
          this.editedIndex = -1
        })
      },

      save () {
         
        if (this.editedIndex > -1) {
          // update
          axios.put('/api/update-leave',this.editedItem)
            .then((res) => {
                        console.log(res);
                          if(res.data.message == 'empty')
                        {
                            alert('Leave credit reached the limit');
                        }
                        else
                        {
                            alert('leave credits added');
                        }
					})
            this.getLeaves();
          // Object.assign(this.leaves[this.editedIndex], this.editedItem)
        } else {
          // new
          axios.post('/api/request-leave',this.editedItem)
            .then((res) => {
                        console.log(res);
                          if(res.data.message == 'empty')
                        {
                            alert('Leave credit reached the limit');
                        }
                        else
                        {
                            alert('leave credits added');
                        }
					})
          // axios.post('/api/request-leave', this.editItem ).then(({data})=>{
				
			  // })
          this.getLeaves();
          // this.leaves.push(this.editedItem)
        }
        this.close()
      },
    },
  }
</script>