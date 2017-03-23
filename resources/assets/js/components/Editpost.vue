<template>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.css">
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <a v-if="post.status == 'approved'" href="/blog/{{post.slug}}" target="blank"  class="btn btn-lg btn-flat btn-success" role="button" style="margin-bottom: 15px;">
                    View Post
                </a>
                <div class="box box-info">
                    <div class="box-header">
                        <h3 class="box-title">Post Editor
                            <small>Markdown Editor</small>
                        </h3>
                    </div>
                    <div class="box-body pad">
                        <div class="form-horizontal" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="title" class="col-sm-1 control-label">Title</label>
                                <div class="col-sm-11">
                                    <input class="form-control" id="title" placeholder="title" v-model="post.title">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="categories" class="col-sm-1 control-label">Select categories</label>
                                <div class="col-sm-11">
                                    <multiselect :selected.sync="post.categories.data" :options="options2" :multiple="true"
                                    key="hashid" label="name" :on-change="onChangeAction"
                                    ></multiselect>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="description" class="col-sm-1 control-label">Description</label>
                                <div class="col-sm-11">
                                    <textarea rows="3" class="form-control" id="description" placeholder="description" v-model="post.description"></textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="content" class="col-sm-1 control-label">Contenu</label>
                                <div class="col-sm-11">
                                    <textarea id="mdeditor" name="content"></textarea>
                                </div>
                            </div>

                            <button v-if="isPublished" type="button" @click="publishPost(post)" class="btn btn-lg btn-success btn-flat pull-right"> Publish Post </button>
                            <button type="button" @click="updatePost(post)" class="btn btn-lg btn-primary btn-flat pull-right">Save Post</button>
                            <button class="btn btn-danger btn-flat btn-lg"  @click="deletePost(post)">Delete</button>
                            <div class="clearfix"><br></div>
                            <div class="form-group">
                                <div class="col-sm-3">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Featured Image</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><dropzone :model='post.image' :action="'/api/posts/'+postId+'/image'"></dropzone></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-sm-9">
                                    <table v-if="medias" class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Image</th>
                                                <th>Options</th>
                                                <th>Title</th>
                                                <th>Description</th>
                                                <th>Disk</th>
                                                <th>Size</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="" v-for="media in medias">
                                                <td><img height="100" src="{{media.url}}" alt="{{media.alt}}"></td>
                                                <td>
                                                    <button @click="putInFront(media)" v-if="media.collection_name != 'featured'" class="btn btn-success" name="button">Featured</button>
                                                    <button @click="putInDescription(media)" class="btn btn-primary" name="button">Insert</button>
                                                    <button v-link="{ name: 'editmedia', params: {hashid: media.hashid}}" class="btn btn-info" name="button">Edit</button>
                                                    <button v-if="media.collection_name != 'featured'" @click="deleteMedia(media)" class="btn btn-danger" name="button">Remove</button>
                                                </td>
                                                <td>{{media.title}}</td>
                                                <td>{{media.description}}</td>
                                                <td>{{media.disk}}</td>
                                                <td>{{media.size}} {{media.model_id}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col-->
        </div>
        <!-- ./row -->
    </section>
</template>
<script>
import SimpleMDE from 'simplemde'
import Dropzone from './Dropzone.vue'
import Multiselect from 'vue-multiselect/lib/vue-multiselect.js'
import {stack_bottomright, show_stack_success, show_stack_error} from '../Pnotice.js'

export default {
    created() {
        this.postId = this.$route.params.hashid
        this.fetchPost()
        this.fetchCategories()
        this.fetchMedias()
    },
    components: {
        Dropzone,
        Multiselect
    },
    ready(){
        this.simplemde = new SimpleMDE({
            element: document.getElementById("mdeditor"),
            spellChecker: false,
        });
    },
    data () {
        return {
            post: {
                categories: {
                    data: []
                }
            },
            postId: '',
            simplemde: '',
            options2: [],
            medias: [],
            values: []
        }
    },
    methods: {
        fetchPost () {
            this.$http({
                url: '/api/posts/' + this.postId + '?include=categories',
                method: 'GET'
            }).then(function (response) {
                this.$set('post', response.data)
                if(this.simplemde.value() == ""){
                    this.simplemde.value(this.post.content);
                }
                this.$set('values', response.data.categories.data)
            })
        },
        updatePost (post) {
            post.content = this.simplemde.value()
            return new Promise((resolve, reject) => {
                this.$http.patch('/api/posts/' + post.hashid, post).then((response) => {
                    show_stack_success('Post saved!', response)
                    resolve()
                }, function (response) {
                    show_stack_error('Failed to save post!', response)
                    reject()
                });
            })
        },
        putInFront (media) {
            return new Promise((resolve, reject) => {
                this.$http.patch('/api/posts/putInFront/' + media.hashid, media).then((response) => {
                    show_stack_success('Media featured changed for this post !', response)
                    resolve()
                    this.fetchPost()
                    this.fetchMedias()
                }, function (response) {
                    show_stack_error('Failed to update the featured media!', response)
                    reject()
                    this.fetchMedias()
                });
            })

        },
        putInDescription (media) {
            this.simplemde.value(this.simplemde.value().concat("!["+media.alt+"]("+"http://localhost:8000"+media.url+")"))

        },
        publishPost (post) {
            if (this.values === undefined || this.values == 0) {
                swal('Sorry', 'Please add a category before publishing this post!', 'info')
            } else {
                let self = this
                swal({
                    title: 'You are about to publish this post!',
                    text: 'Are you sure?',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, publish it!',
                    cancelButtonText: 'No, not yet.',
                }).then(function () {
                    self.updatePost(post).then(() => {
                        self.$http.post('/api/posts/' + post.hashid + '/publish', post).then(function (response) {
                            swal(
                                'Published!',
                                'Your post has been published to the world!.',
                                'success'
                            );
                            this.$router.go('/posts/')
                        }, function (response) {
                            show_stack_error('Failed to publish post!', response)
                        })
                    })
                }, function (dismiss) {
                    // dismiss can be 'cancel', 'overlay', 'close', 'timer'
                    if (dismiss === 'cancel') {
                        swal(
                            'Cancelled',
                            'Your post is safe :)',
                            'error'
                        );
                    }
                });
            }
        },
        fetchCategories () {
            this.$http({url: '/api/categories', method: 'GET'}).then(function (response) {
                this.$set('options2', response.data.data)
            })
        },
        fetchMedias () {
            this.$http({url: '/api/posts/medias/' + this.postId, method: 'GET'}).then(function (response) {
                this.$set('medias', response.data.data)
            })
        },
        onChangeAction (value) {
            this.values = value
            this.$http.patch('/api/posts/' + this.postId + '/categories', {categories: value}).then((response) => {
                show_stack_success('Categories updated!', response)
            }, function (response) {
                show_stack_error('Failed update categories!', response)
            });
        },
        deletePost (post) {
            let self = this
            swal({
                title: 'Are you sure?',
                text: 'You will not be able to recover this post!',
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, keep it',
            }).then(function () {
                self.$http.delete('/api/posts/' + post.hashid, post).then(function (response) {
                    self.$router.go('/posts')
                    swal(
                        'Deleted!',
                        'Your post has been deleted.',
                        'success'
                    );
                }, function (response) {
                    show_stack_error('Deletion of post went wrong.', response)
                })
            }, function (dismiss) {
                // dismiss can be 'cancel', 'overlay', 'close', 'timer'
                if (dismiss === 'cancel') {
                    swal(
                        'Cancelled',
                        'Your post is safe :)',
                        'error'
                    );
                }
            });
        },
        deleteMedia (media) {
            let self = this
            swal({
                title: 'Are you sure?',
                text: 'You will not be able to recover this media!',
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, keep it',
            }).then(function () {
                self.$http.delete('/api/medias/' + media.hashid, media).then(function (response) {
                    this.fetchMedias()
                    swal(
                        'Deleted!',
                        'Your media has been deleted.',
                        'success'
                    );
                }, function (response) {
                    show_stack_error('Deletion of media went wrong.', response)
                })
            }, function (dismiss) {
                // dismiss can be 'cancel', 'overlay', 'close', 'timer'
                if (dismiss === 'cancel') {
                    swal(
                        'Cancelled',
                        'Your media is safe :)',
                        'error'
                    );
                }
            });
        }
    },
    computed: {
        isPublished: function () {
            if (this.post.status !== 'approved') {
                return true
            } else {
                return false
            }
        }
    }
}
</script>
