<template>
    <h1>Users</h1>
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Users</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body table-responsive no-padding">
                        <table class="table table-hover">
                            <tr>
                                <th>Avatar</th>
                                <th>Full name</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Bio</th>
                                <th>Registration</th>
                            </tr>
                            <tr v-for="user in users">
                                <td><img src="{{user.avatar}}" alt="{{user.username}}"></td>
                                <td>{{user.name}}</td>
                                <td>{{user.username}}</td>
                                <td>{{user.email}}</td>
                                <td>{{user.bio}}</td>
                                <td>{{user.created | moment "from" "now" }}</td>
                            </tr>
                        </table>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
        </div>
    </section>
</template>

<script>
export default {
    created () {
    },
    ready () {
        this.fetchUsers()
        // console.log( this.VueMoment.moment().local())
    },
    data () {
        return {
            users: []
        }
    },

    methods: {
        fetchUsers() {
            this.$http({url: '/api/users', method: 'GET'}).then(function (response) {
                this.$set('users', response.data.data)
            })
        },
        refresh () {
            location.reload();
        }
    },
    // computed: {
    //     resource_url: function () {
    //         return '/api/users'
    //     }
    // }
}
</script>
