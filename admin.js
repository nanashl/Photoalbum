function editProduct(product) {
    document.getElementById('productId').value = product.id;
    document.getElementById('formAction').value = 'update';
    document.getElementById('title').value = product.title;
    document.getElementById('description').value = product.description;
    document.getElementById('price').value = product.price;
    document.getElementById('existingImage').value = product.image_url;
}
