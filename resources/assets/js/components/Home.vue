<template>
    <div class="row">
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="fa fa-list"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Posts</span>
                    <span class="info-box-number">{{posts.length}}</span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-orange"><i class="fa fa-th-large"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Categories</span>
                    <span class="info-box-number">{{categories.length}}</span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-purple"><i class="fa fa-users"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Users</span>
                    <span class="info-box-number">{{users.length}}</span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-red"><i class="fa fa-upload"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Medias</span>
                    <span class="info-box-number">{{medias.length}}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-9">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">Latest Posts</h3>

                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table no-margin">
                            <thead>
                                <tr>
                                    <th>Image</th>
                                    <th>Title</th>
                                    <th>Description </th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="post in posts">
                                    <td><img style="width:100%;" src="{{post.image}}" alt=""></td>
                                    <td><a href="{{post.slug}}">{{post.title}}</a></td>
                                    <td>{{post.description}}</td>

                                    <td>
                                        <span v-if="post.status == 'approved'" class="label label-success">{{post.status}}</span>
                                        <span v-if="post.status != 'approved'" class="label label-warning">{{post.status}}</span>
                                    </td>
                                </tr>
                                <!--
                                'hashid' => Hashids::encode($post->id),
                                'title' => $post->title,
                                'slug' => $post->slug,
                                'description' => $post->description,
                                'image' => $post->image_url,
                                'content' => $post->content,
                                'status' => $post->getHumanStatus()
                            -->
                        </tbody>
                    </table>
                </div>
                <!-- /.table-responsive -->
            </div>
            <!-- /.box-body -->
            <div class="box-footer clearfix">
                <a href="javascript:void(0)" class="btn btn-sm btn-info btn-flat pull-left">Place New Order</a>
                <a href="javascript:void(0)" class="btn btn-sm btn-default btn-flat pull-right">View All Orders</a>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Latest Members</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
            </div>
            <div class="box-body no-padding">
                <ul class="users-list clearfix">
                    <li v-for="user in users">
                        <img src="{{user.avatar}}" alt="User Image">
                        <a class="users-list-name" href="#">{{user.name}}</a>
                        <span class="users-list-date">Today</span>
                    </li>
                </ul>
            </div>
            <div class="box-footer text-center">
                <a v-link="{ path: '/users' }" class="uppercase">View All Users</a>
            </div>
        </div>
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Latest Categories</h3>

                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table no-margin">
                        <thead>
                            <tr>
                                <th>Icon</th>
                                <th>Name</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="category in categories">
                                <td><i class="{{category.icon}}"></i></td>
                                <td><a href="{{category.hashid}}">{{category.name}}</a></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- /.table-responsive -->
            </div>
            <!-- /.box-body -->
            <div class="box-footer clearfix">
                <a href="javascript:void(0)" class="btn btn-sm btn-success btn-flat pull-left"><i class="fa fa-plus"></i></a>
                <a href="javascript:void(0)" class="btn btn-sm btn-info btn-flat pull-right">View All Categories</a>
            </div>
        </div>
    </div>
</div>
</template>

<script>
export default {
    ready () {
        this.fetchPosts()
        this.fetchCategories()
        this.fetchUsers()
        this.fetchMedias()
    },
    data () {
        return {
            posts: [],
            categories:[],
            users:[],
            medias:[]
        }
    },
    methods: {
        fetchPosts () {
            this.$http({url: '/api/posts', method: 'GET'}).then(function (response) {
                this.$set('posts', response.data.data)
            })
        },
        fetchCategories() {
            this.$http({url: '/api/categories', method: 'GET'}).then(function (response) {
                this.$set('categories', response.data.data)
            })
        },
        fetchUsers() {
            this.$http({url: '/api/users', method: 'GET'}).then(function (response) {
                this.$set('users', response.data.data)
            })
        },
        fetchMedias() {
            this.$http({url: '/api/medias', method: 'GET'}).then(function (response) {
                this.$set('medias', response.data.data)
            })
        }
    }
}
</script>
