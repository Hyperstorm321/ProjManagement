<template>
    <nav class="font-body">
        <v-navigation-drawer v-model="drawer" app color="#292935">
            <v-list dense>
                <v-list class="mb-5">
                    <v-row>
                        <v-col class="ml-8">
                            <v-avatar color="#760D11" size="70">
                                <span class="white--text headline">A</span>
                            </v-avatar>
                        </v-col>
                        <v-col class="mr-12">
                            <span class="grey--text text--ligthen-1"
                                ><v-icon color="green" size="15" class="mr-2"
                                    >mdi-checkbox-blank-circle</v-icon
                                >Online</span
                            >
                            <span class="white--text">{{
                                user_data.first_name
                            }}</span>
                            <span class="indigo--text">{{
                                user_data.user_type
                            }}</span>
                        </v-col>
                    </v-row>
                </v-list>
                <v-divider class="mr-12 ml-12 mb-8 divider"></v-divider>
                <v-list-item-group
                    v-model="group"
                    active-class="white--text font-weight-bold"
                >
                    <v-list-item link v-on:click="manageRoom">
                        <v-list-item-action class="pl-5">
                            <v-icon color="#FFFFFF"
                                >mdi-laptop-chromebook</v-icon
                            >
                        </v-list-item-action>
                        <v-list-item-content class="white--text">
                            Manage Rooms
                        </v-list-item-content>
                    </v-list-item>
                    <v-list-item link v-on:click="manageExam">
                        <v-list-item-action class="pl-5">
                            <v-icon color="#FFFFFF"
                                >mdi-newspaper-variant-outline</v-icon
                            >
                        </v-list-item-action>
                        <v-list-item-content class="white--text">
                            Manage Exams
                        </v-list-item-content>
                    </v-list-item>
                    <v-list-item link>
                        <v-list-item-action class="pl-5">
                            <v-icon color="#FFFFFF"
                                >mdi-account-reactivate</v-icon
                            >
                        </v-list-item-action>
                        <v-list-item-content class="white--text">
                            Account
                        </v-list-item-content>
                    </v-list-item>
                    <v-list-item link v-on:click="logout">
                        <v-list-item-action class="pl-5">
                            <v-icon color="#FFFFFF">mdi-logout</v-icon>
                        </v-list-item-action>
                        <v-list-item-content class="white--text">
                            Logout
                        </v-list-item-content>
                    </v-list-item>
                </v-list-item-group>
            </v-list>
            <template v-slot:append>
                <div class="pa-2 text-center mb-5">
                    <img src="../../assets/img/vixam_logo.png" />
                </div>
            </template>
        </v-navigation-drawer>

        <v-app-bar app color="primary" dark>
            <v-app-bar-nav-icon
                @click.stop="drawer = !drawer"
            ></v-app-bar-nav-icon>
            <!-- <v-toolbar-title>Vixam</v-toolbar-title> -->
        </v-app-bar>
    </nav>
</template>

<style scoped>
@import url("https://fonts.googleapis.com/css2?family=Poppins&display=swap");
.font-body {
    font-family: "Poppins", sans-serif;
}
.divider {
    border: 1px solid grey !important;
}
</style>

<script>
export default {
    data() {
        return {
            user_data: {
                first_name: "",
                user_type: ""
            },
            drawer: true,
            group: null
        };
    },
    mounted() {
        this.userDetails();
    },
    methods: {
        userDetails() {
            axios.get("api/user").then(response => {
                this.user_data.first_name = response.data.first_name;
                var userType = response.data.user_type_id;
                if (userType == 1) {
                    this.user_data.user_type = "Student";
                } else if (userType == 2) {
                    this.user_data.user_type = "Instructor";
                } else {
                    this.user_data.user_type = "ERROR";
                }
            });
        },
        logout() {
            axios
                .post("/logout")
                .then(response => {
                    this.$router.push({ name: "Index" });
                    localStorage.clear();
                })
                .catch(err => {
                    console.log("please call an administrator");
                });
        },
        manageRoom() {
            this.$router.push({ name: "ManageRoom" });
        },
        manageExam() {
            this.$router.push({ name: "ManageExam" });
        }
    }
};
</script>
