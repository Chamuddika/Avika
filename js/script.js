function showAlert(title, icon) {
  const Toast = Swal.mixin({
    toast: true,
    position: "top-end",
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
      toast.onmouseenter = Swal.stopTimer;
      toast.onmouseleave = Swal.resumeTimer;
    },
  });
  return Toast.fire({
    icon: icon,
    title: title,
  });
}

const registerForm = document.getElementById("registerForm");
if (registerForm) {
  const btn = document.getElementById("registerBtn");
  registerForm.addEventListener("submit", async function (event) {
    event.preventDefault();
    btn.disabled = true;
    const formData = new FormData(this);
    const verification_code = Math.floor(100000 + Math.random() * 900000);
    formData.append("verification_code", verification_code);

    try {
      const response = await fetch("signUpProcess.php", {
        method: "POST",
        body: formData,
      });
      const result = await response.json();
      if (result.status === "success") {
        showAlert(result.message, "success");
        window.location = "login.php";
        btn.disabled = false;
      } else {
        showAlert(result.message, "error");
        btn.disabled = false;
      }
    } catch (error) {
      console.error("Error submitting form:", error);
    }
  });
}

function simulateVerification() {
  const btn = document.getElementById("verifyBtn");
  if (!btn) return;

  btn.innerHTML =
    '<span class="spinner-border spinner-border-sm me-2"></span> Verifying...';
  btn.disabled = true;
}

document.addEventListener("DOMContentLoaded", function () {
  const verifyForm = document.getElementById("verifyForm");
  if (verifyForm) {
    // Simulate auto-verification after 3 seconds
    setTimeout(async () => {
      simulateVerification();
      const verifyEmailForm = document.getElementById("verifyEmailForm");
      const formData = new FormData(verifyEmailForm);
      try {
        const response = await fetch("verify-process.php", {
          method: "POST",
          body: formData,
        });
        const result = await response.json();
        if (result.status === "success") {
          document.getElementById("pendingState").style.display = "none";
          document.getElementById("successState").style.display = "block";
        } else {
          showAlert(result.message, "error");
        }
      } catch (error) {
        console.error(error);
      }
    }, 1000);
  }
});

// Handle manual button click
const verifyEmailForm = document.getElementById("verifyEmailForm");
if (verifyEmailForm) {
  verifyEmailForm.addEventListener("submit", async function (event) {
    simulateVerification();
    event.preventDefault();
    const formData = new FormData(this);
    try {
      const response = await fetch("verify-process.php", {
        method: "POST",
        body: formData,
      });
      const result = await response.json();
      if (result.status === "success") {
        document.getElementById("pendingState").style.display = "none";
        document.getElementById("successState").style.display = "block";
      } else {
        showAlert(result.message, "error");
      }
    } catch (error) {
      console.error(error);
    }
  });
}

const signInFrom = document.getElementById("signInFrom");
if (signInFrom) {
  const btn = document.getElementById("signInBtn");
  signInFrom.addEventListener("submit", async function (event) {
    event.preventDefault();
    btn.disabled = true;
    const formData = new FormData(this);
    const rememberMe = document.getElementById("rememberMe");
    formData.append("rememberMe", rememberMe.checked);

    try {
      const response = await fetch("signInProcess.php", {
        method: "POST",
        body: formData,
      });
      const result = await response.json();
      if (result.status === "success") {
        window.location = "index.php";
        btn.disabled = false;
      } else {
        showAlert(result.message, "error");
        btn.disabled = false;
      }
    } catch (error) {
      console.error("Error submitting form:", error);
    }
  });
}

var bm;
function showModel() {
  var m = document.getElementById("forgotPassword");
  bm = new bootstrap.Modal(m);
  bm.show();
}

const emailSendFrom = document.getElementById("emailFrom");
if (emailSendFrom) {
  const btn = document.getElementById("sendBtn");
  emailSendFrom.addEventListener("submit", async function (event) {
    btn.disabled = true;
    event.preventDefault();
    const formData = new FormData(emailSendFrom);
    const verification_code = Math.floor(100000 + Math.random() * 900000);
    formData.append("verification_code", verification_code);
    try {
      const response = await fetch("emailSendProcess.php", {
        method: "POST",
        body: formData,
      });
      const result = await response.json();
      if (result.status === "success") {
        var m = document.getElementById("forgotPasswordModal");
        bm = new bootstrap.Modal(m);
        bm.show();
        btn.disabled = false;
      } else {
        showAlert(result.message, "error");
        btn.disabled = false;
      }
    } catch (error) {
      console.error("Error submitting form:", error);
    }
  });
}

const forgotPasswordModal = document.getElementById("forgotPasswordForm");
if (forgotPasswordModal) {
  const btn = document.getElementById("submitBtn");
  forgotPasswordModal.addEventListener("submit", async function (event) {
    btn.disabled = true;
    event.preventDefault();
    const formData = new FormData(forgotPasswordModal);
    const email = document.getElementById("email2");
    formData.append("email", email.value);
    try {
      const response = await fetch("forgotPasswordProcess.php", {
        method: "POST",
        body: formData,
      });
      const result = await response.json();
      if (result.status === "success") {
        showAlert(result.message, "success");
        btn.disabled = false;
        window.location.reload();
        bm.hide();
      } else {
        showAlert(result.message, "error");
        btn.disabled = false;
      }
    } catch (error) {
      console.error("Error submitting form:", error);
    }
  });
}

function signout() {
  var r = new XMLHttpRequest();

  r.onreadystatechange = function () {
    if (r.readyState == 4 && r.status == 200) {
      var t = r.responseText;
      console.log(t);
      if (t == "success") {
        showAlert("SignOut Process is Successfully.", "success");
        window.location.reload();
      } else {
        showAlert(t, "error");
      }
    }
  };

  r.open("GET", "signoutProcess.php", true);
  r.send();
}

const adminLoginForm = document.getElementById("adminLoginForm");
if (adminLoginForm) {
  const btn = document.getElementById("adminSigninBtn");
  adminLoginForm.addEventListener("submit", async function (event) {
    event.preventDefault();
    btn.disabled = true;
    const formData = new FormData(this);
    try {
      const response = await fetch("adminSignInProcess.php", {
        method: "POST",
        body: formData,
      });
      const result = await response.json();
      if (result.status === "success") {
        window.location = "admin-dashboard.php";
        btn.disabled = false;
      } else {
        showAlert(result.message, "error");
        btn.disabled = false;
      }
    } catch (error) {
      console.error("Error submitting form:", error);
    }
  });
}

const productImage = document.getElementById("productImage");
const imageBox = document.getElementById("imageBox");
const imagePreview = document.getElementById("imagePreview");

if (imageBox) {
  imageBox.addEventListener("click", () => {
    productImage.click();
  });
}

if (productImage) {
  productImage.addEventListener("change", function () {
    const file = this.files[0];

    if (!file) return;

    const reader = new FileReader();

    reader.onload = function (e) {
      imagePreview.src = e.target.result;
    };

    reader.readAsDataURL(file);
  });
}

// VIDEO PREVIEW
const videoInput = document.getElementById("videoInput");
const videoThumbnail = document.getElementById("videoThumbnail");
const videoPreview = document.getElementById("videoPreview");

if (videoThumbnail) {
  videoThumbnail.addEventListener("click", () => {
    videoInput.click();
  });
}

if (videoPreview) {
  videoPreview.addEventListener("click", () => {
    videoInput.click();
  });
}

if (videoInput) {
  videoInput.addEventListener("change", function () {
    const file = this.files[0];
    if (!file) return;
    const url = URL.createObjectURL(file);
    videoPreview.src = url;
    videoThumbnail.classList.toggle("d-none");
    videoPreview.style.display = "block";
  });
}

const addProductForm = document.getElementById("addProductForm");
if (addProductForm) {
  const btn = document.getElementById("btn");
  addProductForm.addEventListener("submit", async function (event) {
    event.preventDefault();
    btn.disabled = true;

    const formData = new FormData(this);
    let url = "addProductProcess.php";
    if (addProductForm.dataset.mode == "edit") {
      url = "updateProductProcess.php";
    }
    try {
      const response = await fetch(url, {
        method: "POST",
        body: formData,
      });
      const result = await response.json();
      if (result.status === "success") {
        showAlert(result.message, "success");
        window.location = "manage-product.php";
        btn.disabled = false;
      } else {
        showAlert(result.message, "error");
        btn.disabled = false;
      }
    } catch (error) {
      console.error("Error submitting form:", error);
    }
  });
}

function qty_inc(qty) {
  var input = document.getElementById("qty_input");

  if (input.value < qty) {
    var newValue = parseInt(input.value) + 1;
    input.value = newValue.toString();
  } else {
    showAlert("Maximum quantity has achieved.", "error");
    input.value = qty;
  }
}

function qty_dec() {
  var input = document.getElementById("qty_input");

  if (input.value > 1) {
    var newValue = parseInt(input.value) - 1;
    input.value = newValue.toString();
  } else {
    showAlert("Minimum quantity has achieved", "error");
  }
}

function check_value(qty) {
  var input = document.getElementById("qty_input");

  if (input.value <= 0) {
    showAlert("Quantity must be 1 or more.", "error");
    input.value = qty;
  } else if (input.value > qty) {
    showAlert("Insufficient Quantity.", "error");
    input.value = qty;
  }
}

function addCart(id) {
  var qty = document.getElementById("qty_input");

  var f = new FormData();
  f.append("qty", qty.value);
  f.append("id", id);

  var r = new XMLHttpRequest();

  r.onreadystatechange = function () {
    if (r.status == 200 && r.readyState == 4) {
      var t = r.responseText;
      if (t == "success") {
        showAlert("New Product added to the Cart", "success").then(() => {
          window.location.reload();
        });
      } else if (t == "update") {
        showAlert("Update Finished", "success").then(() => {
          window.location.reload();
        });
      } else {
        showAlert(t, "error");
      }
    }
  };

  r.open("POST", "addCartProcess.php", true);
  r.send(f);
}

const buyBtn = document.getElementById("buyNowBtn");
if (buyBtn) {
  buyBtn.addEventListener("click", async function (e) {
    e.preventDefault();
    const sid = this.dataset.productId;
    const qty = document.getElementById("qty_input").value;
    try {
      const response = await fetch(`payNowProcess.php?id=${sid}&qty=${qty}`);
// console.log( response.json());
      const resp = await response.json();
      if (resp.status === "1") {
        showAlert("Please Log In or Sign Up", "error").then(() => {
          window.location = "index.php";
        });
      } else if (resp.status === "2") {
        showAlert("Please Update your Profile First", "error").then(() => {
          window.location = "profile.php";
        });
      } else if (resp.status === "3") {
        showAlert("Please Set Your Default Address", "error").then(() => {
          window.location = "profile.php";
        });
      } else if (resp.status === "4") {
        showAlert("Your Account has bean banned.", "error").then(() => {
          window.location = "login.php";
        });
      } else if (resp.status === "success") {
        payhere.onCompleted = function (orderId) {
          console.log("Payment completed. OrderID:", orderId);
          saveInvoice(orderId, sid, qty);
        };
        payhere.onDismissed = function () {
          showAlert("Payment dismissed.", "error");
        };
        payhere.onError = function (error) {
          showAlert(error, "error");
        };
        payhere.startPayment(resp.payment);
      } else {
        showAlert(resp.message || resp, "error");
      }
    } catch (error) {
      console.error(error);
      showAlert("Something went wrong.", "error");
    }
  });
}

function saveInvoice(orderId, sid, qty) {
  var f = new FormData();
  f.append("oid", orderId);
  f.append("sid", sid);
  f.append("qty", qty);
  var r = new XMLHttpRequest();

  r.onreadystatechange = function () {
    if (r.status == 200 && r.readyState == 4) {
      var t = r.responseText;
      if (t == 1) {
        window.location = "invoice.php?id=" + orderId;
      } else {
        showAlert(t, "error");
      }
    }
  };

  r.open("POST", "saveInvoice.php", true);
  r.send(f);
}

const userDetailsFrom = document.getElementById("userDetails");
if (userDetailsFrom) {
  const btn = document.getElementById("saveChange");
  userDetailsFrom.addEventListener("submit", async function (event) {
    event.preventDefault();
    btn.disabled = true;
    const formData = new FormData(this);
    try {
      const response = await fetch("updateUserProfile.php", {
        method: "POST",
        body: formData,
      });
      const result = await response.json();
      if (result.status === "success") {
        showAlert(result.message, "success");
        btn.disabled = false;
      } else {
        showAlert(result.message, "error");
        btn.disabled = false;
      }
    } catch (error) {
      console.error("Error submitting form:", error);
    }
  });
}

const addressFrom = document.getElementById("addressForm");
if (addressFrom) {
  const btn = document.getElementById("saveAddress");

  addressFrom.addEventListener("submit", async function (event) {
    event.preventDefault();
    btn.disabled = true;
    const addressId = document.getElementById("address_id").value;
    const url = addressId ? "updateAddressProcess.php" : "addNewAddress.php";
    const formData = new FormData(this);
    try {
      const response = await fetch(url, {
        method: "POST",
        body: formData,
      });
      const result = await response.json();
      if (result.status === "success") {
        showAlert(result.message, "success");
        btn.disabled = false;
        window.location.reload();
      } else {
        showAlert(result.message, "error");
        btn.disabled = false;
      }
    } catch (error) {
      console.error("Error submitting form:", error);
    }
  });
}

function editAddress(id) {
  fetch("getAddress.php?id=" + id)
    .then((response) => response.json())
    .then((result) => {
      if (result.status === "success") {
        const data = result.data;

        document.getElementById("address_id").value = data.id;
        document.getElementById("title").value = data.title;
        document.getElementById("line_one").value = data.line_one;
        document.getElementById("line_two").value = data.line_two;
        document.getElementById("city").value = data.city;
        document.getElementById("postal_code").value = data.postal_code;

        document.getElementById("defaultAddress").checked =
          data.is_default == 1;

        document.getElementById("addressModalTitle").innerText = "Edit Address";

        const modal = new bootstrap.Modal(
          document.getElementById("addressModal"),
        );

        modal.show();
      } else {
        showAlert(result.message, "error");
      }
    })
    .catch((error) => {
      console.error(error);
    });
}

function deleteFromCart(id) {
  var r = new XMLHttpRequest();

  r.onreadystatechange = function () {
    if (r.readyState == 4 && r.status == 200) {
      var t = r.responseText;
      if (t == "success") {
        showAlert("Product Remove Successfully.", "success").then(() => {
          window.location.reload();
        });
      } else {
        showAlert(t, "error");
      }
    }
  };

  r.open("GET", "removeCartProcess.php?id=" + id, true);
  r.send();
}

const defaultAddressCheckbox = document.getElementById("useDefaultAddress");
const fields = [
  "first_name",
  "last_name",
  "email",
  "mobile",
  "address1",
  "address2",
  "city",
  "postal_code",
];

if (defaultAddressCheckbox) {
  defaultAddressCheckbox.addEventListener("change", async function () {
    if (this.checked) {
      try {
        const response = await fetch("getDefaultAddress.php");
        const result = await response.json();

        if (result.status === "success") {
          const data = result.data;
          const nameParts = data.name.split(" ");

          document.getElementById("first_name").value = nameParts[0] || "";
          document.getElementById("last_name").value = nameParts
            .slice(1)
            .join(" ");
          document.getElementById("email").value = data.email;
          document.getElementById("mobile").value = data.mobile;
          document.getElementById("address1").value = data.line_one;
          document.getElementById("address2").value = data.line_two;
          document.getElementById("city").value = data.city;
          document.getElementById("postal_code").value = data.postal_code;
          fields.forEach((id) => {
            document.getElementById(id).setAttribute("readonly", true);
          });
        } else {
          showAlert("Please set a default address first.", "error");
          this.checked = false;
        }
      } catch (error) {
        console.error(error);
        showAlert("Failed to load address.", "error");
        this.checked = false;
      }
    } else {
      fields.forEach((id) => {
        const field = document.getElementById(id);

        field.removeAttribute("readonly");
        field.value = "";
      });
    }
  });
}

const checkoutForm = document.getElementById("checkoutForm");
if (checkoutForm) {
  checkoutForm.addEventListener("submit", async function (event) {
    event.preventDefault();
    const submitBtn = checkoutForm.querySelector('button[type="submit"]');
    const originalBtnText = submitBtn.innerHTML;
    submitBtn.innerHTML =
      '<span class="spinner-border spinner-border-sm me-2"></span> Processing...';
    submitBtn.disabled = true;
    try {
      const response = await fetch("paymentNowProcess.php?cart=true");
      const resp = await response.json();
      if (resp.status == "1") {
        showAlert("Please Log In or Sign up", "error").then(() => {
          window.location = "index.php";
        });
      } else if (resp.status == "2") {
        showAlert("Please Update your Profile First", "error").then(() => {
          window.location = "profile.php";
        });
      } else if (resp.status === "3") {
        showAlert("Your Account has bean banned.", "error").then(() => {
          window.location = "login.php";
        });
      } else if (resp.status == "success") {
        odCheckout(resp.payment, "invoices.php");
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalBtnText;
      } else {
        showAlert(resp.error, "error");
      }
    } catch (error) {
      console.error("Error submitting form:", error);
    }
  });
}

function odCheckout(payment, url) {
  payhere.onCompleted = function onCompleted(orderId) {
    console.log("Payment completed. OrderID:" + orderId, "success");
    console.log(invoiceSave(orderId));
  };

  payhere.onDismissed = function onDismissed() {
    showAlert("Payment dismissed.", "error");
  };

  payhere.onError = function onError(error) {
    showAlert(error, "error");
  };

  payhere.startPayment(payment);
}

function invoiceSave(orderId) {
  var f = new FormData(checkoutForm);
  f.append("is_default", defaultAddressCheckbox.checked);
  var r = new XMLHttpRequest();

  r.onreadystatechange = function () {
    if (r.readyState == 4) {
      var t = r.responseText;
      if (t == 1) {
        window.location = "invoice.php?id=" + orderId;
      } else {
        showAlert(t, "error");
      }
    }
  };

  r.open("POST", "saveInvoiceProcess.php?orderId=" + orderId, true);
  r.send(f);
}

function invoice(oid) {
  window.location = "invoice.php?id=" + oid;
}

var mo;
var pid;
function addFeedback(id) {
  var feedbackModal = document.getElementById("reviewModal");
  mo = new bootstrap.Modal(feedbackModal);
  mo.show();
  pid = id;
}

const modalReviewForm = document.getElementById("modalReviewForm");
if (modalReviewForm) {
  modalReviewForm.addEventListener("submit", async function (event) {
    event.preventDefault();
    const formData = new FormData(modalReviewForm);
    formData.append("pid", pid);
    try {
      const response = await fetch("addReviewProcess.php", {
        method: "POST",
        body: formData,
      });
      const result = await response.json();
      if (result.status === "success") {
        mo.hide();
        window.location.reload();
      } else {
        showAlert(result.message, "error");
      }
    } catch (error) {
      console.error("Error submitting form:", error);
    }
  });
}

const priceRange = document.getElementById("priceRange");
const priceText = document.getElementById("priceText");

priceRange.addEventListener("input", () => {
  priceText.innerHTML = "Rs. " + Number(priceRange.value).toLocaleString();
});

document.getElementById("applyFilter").addEventListener("click", loadProducts);
window.addEventListener("load", loadProducts);

function loadProducts(page = 1) {
  const formData = new FormData();

  formData.append("page", page);
  formData.append("price", document.getElementById("priceRange").value);
  let categories = [];

  document.querySelectorAll(".categoryCheck:checked").forEach((item) => {
    categories.push(item.value);
  });
  formData.append("categories", JSON.stringify(categories));

  fetch("filterProducts.php", {
    method: "POST",
    body: formData,
  })
    .then((res) => res.text())
    .then((data) => {
      document.getElementById("productContainer").innerHTML = data;
    });
}

function blockUser(id) {
  var request = new XMLHttpRequest();

  request.onreadystatechange = function () {
    if (request.status == 200 && request.readyState == 4) {
      var txt = request.responseText;
      console.log(txt);
      if (txt == "blocked") {
        document.getElementById("ub" + id).innerHTML =
          '<button class="action-btn btn-delete status-inactive"  id="ub' +
          id +
          '" onclick="blockUser(\'' +
          id +
          '\');"><i class="bi bi-person-dash"></i></button>';
        document.getElementById("ub" + id).classList = "status-active";
        window.location.reload();
      } else if (txt == "unblocked") {
        document.getElementById("ub" + id).innerHTML =
          '<button class="action-btn btn-active status-inactive"  id="ub' +
          id +
          '" onclick="blockUser(\'' +
          id +
          '\');"><i class="bi bi-person-check"></i></button>';
        document.getElementById("ub" + id).classList = "status-inactive";
        window.location.reload();
      } else {
        showAlert(txt, "error");
      }
    }
  };

  request.open("GET", "userBlockProcess.php?id=" + id, true);
  request.send();
}

async function searchUsers() {
  const keyword = document.getElementById("searchUser").value;
  console.log(keyword);
  const formData = new FormData();
  formData.append("keyword", keyword);

  try {
    const response = await fetch("searchUsers.php", {
      method: "POST",
      body: formData,
    });

    const html = await response.text();

    document.getElementById("userTable").innerHTML = html;
  } catch (err) {
    console.log(err);
  }
}

async function updateOrderStatus(orderId, status) {
  const formData = new FormData();
  formData.append("order_id", orderId);
  formData.append("status", status);

  try {
    const response = await fetch("updateOrderStatus.php", {
      method: "POST",
      body: formData,
    });

    const result = await response.json();

    if (result.status === "success") {
      showAlert(result.message, "success");
      window.location.reload();
    } else {
      showAlert(result.message, "error");
    }
  } catch (error) {
    console.error(error);
    showAlert("Something went wrong.", "error");
  }
}

const orderFilter = document.getElementById("orderStatusFilter");
console.log(orderFilter);
orderFilter.addEventListener("change", function () {
  alert("ok");
  const status = this.value;

  fetch("filterOrders.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: "status=" + encodeURIComponent(status),
  })
    .then((res) => res.text())
    .then((data) => {
      document.getElementById("orderTableBody").innerHTML = data;
    });
});

function loadOrders(status) {
  console.log("Loading:", status);

  fetch("filterOrders.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: "status=" + encodeURIComponent(status),
  })
    .then((response) => response.text())
    .then((data) => {
      document.getElementById("orderTableBody").innerHTML = data;
    });
}

function editProduct(stockId) {
  fetch("getProduct.php?id=" + stockId)
    .then((res) => res.json())
    .then((data) => {
      if (data.status == "success") {
        document.getElementById("stock_id").value = data.stock.id;
        document.querySelector("[name='product_name']").value =
          data.product.name;
        document.querySelector("[name='category']").value =
          data.product.category;
        document.querySelector("[name='hair_type']").value =
          data.product.hair_type;
        document.querySelector("[name='price']").value = data.stock.price;
        document
          .querySelector("[name='price']")
          .setAttribute("readonly", "true");
        document.querySelector("[name='quantity']").value = data.stock.qty;
        document.querySelector("[name='weight']").value = data.stock.weight;
        document.querySelector("[name='volume']").value = data.stock.capacity;
        document.querySelector("[name='description']").value =
          data.product.description;
        document.querySelector("[name='ingredients']").value =
          data.product.ingredients;
        document.querySelector("[name='instruction']").value =
          data.product.instruction;
        imagePreview.src = data.product.img_url;
        videoPreview.src = data.product.instruction_video_url;
        videoPreview.style.display = "block";
        videoThumbnail.style.display = "none";
        document.querySelector(".modal-title").innerHTML =
          '<i class="bi bi-pencil-square me-2"></i>Edit Product';
        document.getElementById("btn").innerHTML =
          '<i class="bi bi-check2-circle me-2"></i>Update Product';
        document
          .getElementById("addProductForm")
          .setAttribute("data-mode", "edit");
        new bootstrap.Modal(document.getElementById("addProductModal")).show();
      }
    });
}

function deleteProduct(stockId) {
  Swal.fire({
    title: "Delete Product?",
    text: "This action cannot be undone!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#d33",
    cancelButtonColor: "#3085d6",
    confirmButtonText: "Delete",
  }).then((result) => {
    if (result.isConfirmed) {
      fetch("deleteProductProcess.php?id=" + stockId)
        .then((res) => res.json())
        .then((data) => {
          Swal.fire({
            icon: data.status,
            title: data.message,
            timer: 1500,
            showConfirmButton: false,
          });

          if (data.status == "success") {
            setTimeout(() => {
              location.reload();
            }, 1500);
          }
        });
    }
  });
}

function deleteAddress(id) {
  Swal.fire({
    title: "Delete Address?",
    text: "Are you sure you want to delete this address?",
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "Yes, Delete",
    cancelButtonText: "Cancel",
  }).then((result) => {
    if (result.isConfirmed) {
      const formData = new FormData();
      formData.append("id", id);

      fetch("deleteAddressProcess.php", {
        method: "POST",
        body: formData,
      })
        .then((response) => response.json())
        .then((resp) => {
          if (resp.status === "success") {
            Swal.fire({
              icon: "success",
              title: "Success",
              text: resp.message,
            }).then(() => {
              location.reload();
            });
          } else {
            Swal.fire({
              icon: "error",
              title: "Error",
              text: resp.message,
            });
          }
        })
        .catch((error) => {
          console.error(error);
        });
    }
  });
}
