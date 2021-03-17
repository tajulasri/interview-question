
<template>
  <v-fragment name="dashboard">
    <navigation-menu :role="user.role_id"></navigation-menu>

    <div v-if="showEditModal">
      <transition name="modal">
        <div class="modal-mask">
          <div class="modal-wrapper">
            <div class="modal-dialog modal-lg" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title">Create new user</h5>
                  <button
                    type="button"
                    class="close"
                    data-dismiss="modal"
                    aria-label="Close"
                  >
                    <span aria-hidden="true" @click="showEditModal = false"
                      >&times;</span
                    >
                  </button>
                </div>
                <div class="modal-body">
                  <div class="form-group col-12 row">
                    <label for="email">Email</label>
                    <input
                      type="email"
                      id="email"
                      class="form-control"
                      placeholder="user@example.com"
                      v-model="userForm.email"
                      required
                    />
                  </div>
                  <div class="form-group col-12 row">
                    <label for="name">Name</label>
                    <input
                      type="text"
                      id="name"
                      class="form-control"
                      v-model="userForm.name"
                      placeholder="User's name"
                      required
                    />
                  </div>

                  <div class="form-group col-12 row">
                    <label for="name">Roles</label>
                    <select
                      name="role_id"
                      id="roleId"
                      class="form-control"
                      v-model="userForm.role"
                    >
                      <option
                        v-for="(role, index) in roles"
                        :value="role"
                        :key="role.id"
                      >
                        {{ role.name }}
                      </option>
                    </select>
                  </div>
                </div>
                <div class="modal-footer">
                  <button
                    type="button"
                    class="btn btn-secondary"
                    @click="showEditModal = false"
                  >
                    Close
                  </button>
                  <button
                    type="button"
                    class="btn btn-primary"
                    @click="updateUser()"
                  >
                    Save changes
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </transition>
    </div>

    <div v-if="showModal">
      <transition name="modal">
        <div class="modal-mask">
          <div class="modal-wrapper">
            <div class="modal-dialog modal-lg" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title">Create new user</h5>
                  <button
                    type="button"
                    class="close"
                    data-dismiss="modal"
                    aria-label="Close"
                  >
                    <span aria-hidden="true" @click="showModal = false"
                      >&times;</span
                    >
                  </button>
                </div>
                <div class="modal-body">
                  <div class="form-group col-12 row">
                    <label for="email">Email</label>
                    <input
                      type="email"
                      id="email"
                      class="form-control"
                      placeholder="user@example.com"
                      v-model="userForm.email"
                      required
                    />
                  </div>
                  <div class="form-group col-12 row">
                    <label for="name">Name</label>
                    <input
                      type="text"
                      id="name"
                      class="form-control"
                      v-model="userForm.name"
                      placeholder="User's name."
                      required
                    />
                  </div>

                  <div class="form-group col-12 row">
                    <label for="name">Roles</label>
                    <select
                      name="role_id"
                      id="roleId"
                      class="form-control"
                      v-model="userForm.role"
                    >
                      <option
                        v-for="(role, index) in roles"
                        :value="role"
                        :key="role.id"
                      >
                        {{ role.name }}
                      </option>
                    </select>
                  </div>
                </div>
                <div class="modal-footer">
                  <button
                    type="button"
                    class="btn btn-secondary"
                    @click="showModal = false"
                  >
                    Close
                  </button>
                  <button
                    type="button"
                    class="btn btn-primary"
                    @click="adduser()"
                  >
                    Save changes
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </transition>
    </div>

    <div id="container col-8" style="margin-top: 3em">
      <notifications group="success" />
      <notifications group="error" />
      <div id="content" class="col-8 offset-md-2">
        <div class="card card-default">
          <div class="card-header"><span>Manage Users</span></div>
          <div class="card-body">
            <div
              class=""
              style="margin-top: 1em; margin-bottom: 1em"
              @click="showModal = true"
            >
              <button class="btn btn-primary">Create user</button>
            </div>
            <table class="table table-condensed table-bordered table-hover">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Email</th>
                  <th>Name</th>
                  <th>Role</th>
                  <th>Created At</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <tr v-if="users.length == 0">
                  <td colspan="10" class="text-center">No data available</td>
                </tr>
                <tr v-for="(user, index) in users">
                  <td>{{ index + 1 }}</td>
                  <td>{{ user.email }}</td>
                  <td>{{ user.name }}</td>
                  <td>{{ user.role.name }}</td>
                  <td>{{ user.created_at }}</td>
                  <td>
                    <div class="btn-group">
                      <button
                        type="button"
                        class="btn btn-primary dropdown-toggle"
                        data-toggle="dropdown"
                        aria-haspopup="true"
                        aria-expanded="false"
                      >
                        Action
                      </button>
                      <div class="dropdown-menu">
                        <a class="dropdown-item" @click="editUser(index)"
                          >Edit</a
                        >
                        <a class="dropdown-item" @click="removeUser(index)"
                          >Delete</a
                        >
                      </div>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </v-fragment>
</template>
<script>
import appMixins from "../services/appMixins";
import Menu from "../components/Menu";
import axios from "axios";

export default {
  mixins: [appMixins],
  mounted() {
    this.getUser()
      .then((response) => {
        this.user = response.data;
      })
      .catch((error) => console.log(error.toString()));

    this.userForm.role = this.roles[1];

    axios
      .get("v1/admin/users", {
        headers: { Authorization: `Bearer ${this.getAuthToken()}` },
      })
      .then((response) => (this.users = response.data.data))
      .catch((error) => {
        console.log("display error here");
      });
  },
  data() {
    return {
      showModal: false,
      showEditModal: false,
      loading: false,
      user: null,
      users: [],
      roles: [
        { id: 1, name: "admin" },
        { id: 2, name: "customer" },
      ],
      userForm: { email: null, name: null, role: null },
    };
  },
  methods: {
    adduser() {
      //replace server state to local states
      axios
        .post("v1/admin/users", this.userForm, {
          headers: {
            Authorization: `Bearer ${this.getAuthToken()}`,
          },
        })
        .then((response) => {
          console.log(response.data);

          this.resetForm();
          this.users.push(this.response.data);
          this.showModal = false;

          this.$notify({
            group: "success",
            type: "success",
            title: "Success",
            text: "New user added.",
          });
        })
        .catch((error) => {});
    },
    resetForm() {
      this.userForm = { email: null, name: null };
    },
    removeUser(index) {
      const user = this.users[index];

      axios
        .delete("v1/admin/users/" + user.id, {
          headers: { Authorization: `Bearer ${this.getAuthToken()}` },
        })
        .then((response) => {
          console.log(response.data);
          this.users.splice(index, 1);
        })
        .catch((error) => {
          console.log(error.toString());
        });
    },
    editUser(index) {
      this.userForm = this.users[index];
      this.showEditModal = true;
    },
    updateUser() {
      axios
        .put("v1/admin/users/" + this.userForm.id, this.userForm, {
          headers: { Authorization: `Bearer ${this.getAuthToken()}` },
        })
        .then((response) => {
          this.resetForm();
          this.showEditModal = false;

          this.$notify({
            group: "success",
            type: "success",
            title: "Success",
            text: "User details saved and changed.",
          }).catch((error) => {});
        });

      //replace server state to local states
    },
    updateProfile() {
      axios
        .put("v1/me/param", this.user, {
          headers: { Authorization: `Bearer ${this.getAuthToken()}` },
        })
        .then((response) => {
          console.log(response.data);
          this.$notify({
            group: "success",
            type: "success",
            title: "Success",
            text: response.data.message,
          });
        })
        .catch((error) => {
          this.$notify({
            group: "error",
            title: "Unable to proceed.",
            type: "error",
            text: error.toString(),
          });
        });
    },
  },
  components: {
    "navigation-menu": Menu,
  },
};
</script>