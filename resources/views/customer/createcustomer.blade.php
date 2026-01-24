<form action="{{ route('customers.store') }}" method="POST">
@csrf

<input type="text" name="name" placeholder="Customer Name" required>
<input type="text" name="project_type" placeholder="Project Type">
<input type="text" name="contact_no" placeholder="Contact No">
<input type="text" name="company" placeholder="Company">
<input type="text" name="gst_number" placeholder="GST Number">

<select name="payment_status">
    <option value="pending">Pending</option>
    <option value="paid">Payment Completed</option>
    <option value="balance">Balance</option>
</select>

<button type="submit">Save Customer</button>
</form>
