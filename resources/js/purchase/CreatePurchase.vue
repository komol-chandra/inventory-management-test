<template>
    <div class="card mb-4">
        <h5 class="card-header">Create Purchase</h5>
        <!-- Account -->
        <div class="card-body">
            <form @keydown.enter.prevent.self @submit.prevent="submitPurchase">
                <div class="row mt-2 mb-2">
                    <div class="mb-3 col-md-4">
                        <label for="date" class="form-label">Date <span class="text-danger">*</span> </label>
                        <input class="form-control" type="date" id="date" name="date" v-model="purchase.date"
                               autofocus="">
                    </div>
                    <div class="mb-3 col-md-4">
                        <label for="invoice_id" class="form-label">Invoice No. <span class="text-danger">*</span>
                        </label>
                        <input class="form-control" type="text" id="invoice_id" name="invoice_id"
                               v-model="purchase.invoice_id" autofocus="" disabled>
                    </div>

                    <div class="mb-3 col-md-4">
                        <label for="supplier" class="form-label">Select Supplier <span
                            class="text-danger">*</span></label>
                        <select id="supplier" name="supplier" v-model="purchase.supplier" class="select2 form-select">
                            <option value="Mrs Rohim">Mrs Rohim</option>
                            <option value="Mrs Korim">Mrs Korim</option>
                            <option value="Mrs Jahid">Mrs Jahid</option>
                        </select>
                    </div>
                </div>
                <div class="row mt-2 mb-2">
                    <div class="mb-3 col-md-4">
                        <label for="product_search" class="form-label">Product Name Or SKU <span
                            class="text-danger">*</span> </label>
                        <input class="form-control" type="text" id="product_search" name="product_search"
                               autofocus="" @keyup.enter="getProduct" v-model.trim="sku">
                    </div>
                </div>
                <div class="row mt-2 mb-2" v-if="purchase.products.length > 0">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>SN</th>
                            <th>Product Name</th>
                            <th>Stock</th>
                            <th>Unit</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Total Price</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                        <tr v-for="(product,index) in purchase.products">
                            <td>{{ index + 1 }}</td>
                            <td>{{ product.name }}</td>
                            <td>{{ product.stock }}</td>
                            <td>{{ product.unit }}</td>
                            <td>
                                <input type="number" class="form-control" name="purchase_qty"
                                       v-model.number="product.purchase_qty" @input="calculateTotalPrice(product)">
                            </td>
                            <td>
                                <input type="number" class="form-control" name="purchase_price"
                                       v-model.number="product.purchase_price" @input="calculateTotalPrice(product)">
                            </td>
                            <td>
                                <input type="number" class="form-control" name="total_price" disabled
                                       v-model.number="product.total_price">
                            </td>
                            <td>
                                <button class="btn btn-sm btn-danger" @click="removeProduct(index)"><i
                                    class="bx bx-trash me-1"></i>
                                    Delete
                                </button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <div class="mb-3 col-md-6">
                        <label for="product_search" class="form-label">Note</label>
                        <textarea class="form-control" id="note" name="note" v-model="purchase.note"></textarea>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="grand_total" class="form-label">Grand Total </label>
                        <input class="form-control" type="text" id="grand_total" name="grand_total"
                               disabled v-model="purchase.grand_total">
                    </div>
                </div>
                <div class="mt-2">
                    <button type="submit" class="btn btn-primary me-2" :class="{ disabled: purchase.grand_total === 0 }"
                            @click="savePurchaseData">Save changes
                    </button>
                    <button type="reset" class="btn btn-outline-secondary">Cancel</button>
                </div>
            </form>
        </div>
        <!-- /Account -->
    </div>
</template>
<script>
import {defineComponent} from 'vue'
import axios from "axios";
import Swal from 'sweetalert2';
export default defineComponent({

    name: "CreatePurchase",
    data() {
        return {
            sku: '',
            purchase: {
                date: '',
                invoice_id: '',
                supplier: '',
                grand_total: 0,
                note: '',
                products: [],
            },
        }
    },
    mounted() {
        this.generateInvoiceId(); // Generate invoice ID when the component is mounted
    },
    methods: {
        showAlert(title,body,type) {
            Swal.fire(title, body, title);
        },

        getProduct() {
            axios.get(route('admin.get-product', this.sku))
                .then(response => {
                    this.purchase.products.push(response.data.data);
                    this.showAlert('Ok','Product Added In List','success');
                })
                .catch(error => {
                    this.showAlert('Ok','Product Not Found','error');

                });
        },
        generateRandomText(length) {
            const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            let randomText = '';
            for (let i = 0; i < length; i++) {
                randomText += characters.charAt(Math.floor(Math.random() * characters.length));
            }
            return randomText;
        },
        generateRandomNumber(length) {
            let randomNumber = '';
            for (let i = 0; i < length; i++) {
                randomNumber += Math.floor(Math.random() * 10);
            }
            return randomNumber;
        },
        generateInvoiceId() {
            const randomText = this.generateRandomText(4);
            const randomNum1 = this.generateRandomNumber(4);
            const randomNum2 = this.generateRandomNumber(4);
            const invoiceId = `${randomText}-${randomNum1}-${randomNum2}`;
            this.purchase.invoice_id = invoiceId;
        },
        calculateTotalPrice(product) {
            product.total_price = product.purchase_qty * product.purchase_price;
            this.calculateGrandTotal();
        },
        calculateGrandTotal() {

            this.purchase.grand_total = this.purchase.products.reduce((total, product) => {
                console.log("function" + (total + product.total_price));
                return total + product.total_price;
            }, 0);
        },
        removeProduct(index) {
            this.purchase.products.splice(index, 1);
            this.calculateGrandTotal();
        },

        savePurchaseData() {
            let permission = false;
            if (this.purchase.date && this.purchase.invoice_id && this.purchase.supplier && this.purchase.grand_total && this.purchase.products.length > 0) {
                permission = true;
            }
            if (permission === false) {
                alert("fill every field")
            } else {
                axios.post(route('admin.purchase.store', this.purchase))
                    .then(response => {
                        // Handle success response
                        console.log(response.data);
                        Swal.fire('Ok','Purchase Complete','success').then(() => {
                            window.location.reload();
                        });
                        // Reset the form or perform any other actions
                    })
                    .catch(error => {
                        // Handle error response
                        Swal.fire('Ok','System Error','error').then(() => {
                            window.location.reload();
                        });
                    });
            }
        },
    }
})
</script>
<style scoped>

</style>
