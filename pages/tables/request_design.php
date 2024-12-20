<style>
        body {
            font-family: 'Source Sans Pro', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f3f4f6;
        }
        .wrapper {
            display: flex;
            flex-direction: row;
        }
        .sidebar {
            width: 250px;
            height: 100vh;
            background-color: #343a40;
            color: #fff;
            display: flex;
            flex-direction: column;
            padding: 20px;
            position: fixed;
        }
        .sidebar .nav-link {
            color: #ddd;
            margin: 10px 0;
            font-size: 16px;
            display: flex;
            align-items: center;
        }
        .sidebar .nav-link i {
            margin-right: 10px;
        }
        .sidebar .nav-link:hover {
            color: #fff;
            text-decoration: none;
        }
        .content {
            margin-left: 250px;
            padding: 20px;
            width: 100%;
        }
        .form-container {
            max-width: 850px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            padding: 30px;
        }
        .form-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .form-header h2 {
            font-size: 28px;
            font-weight: 700;
            color: #333;
        }
    </style>


    <style>
    /* General Styling */
    body {
        background-color: #f8f9fa;
        font-family: 'Poppins', sans-serif;
        margin: 0;
        padding: 0;
        color: #333;
    }

    .content {
        padding: 20px;
        max-width: 100%;
        box-sizing: border-box;
        overflow-x: hidden;
    }

    .form-container {
        background-color: #ffffff;
        border-radius: 8px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        padding: 20px;
        margin: auto;
        max-width: 90%;
    }

    .form-header {
        text-align: center;
        margin-bottom: 20px;
        border-bottom: 2px solid #007bff;
        padding-bottom: 10px;
    }

    .form-header h2 {
        font-size: 24px;
        color: #007bff;
        margin: 0;
    }

    .card-item {
        display: flex;
        flex-wrap: wrap;
        align-items: flex-start;
        justify-content: space-between;
        background-color: #fdfdfd;
        border-radius: 6px;
        padding: 15px;
        margin-bottom: 15px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        border: 1px solid #ddd;
    }

    .item-details, .unit-container, .quantity-container {
        flex: 1 1 100%;
        margin-bottom: 15px;
    }

    @media (min-width: 768px) {
        .item-details, .unit-container, .quantity-container {
            flex: 1 1 30%;
        }
    }

    label {
        font-weight: 600;
        font-size: 14px;
        color: #555;
        margin-bottom: 5px;
        display: block;
    }

    .form-control {
        font-size: 14px;
        padding: 8px 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }

    .quantity-control {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 5px;
    }

    .quantity-control button {
        border: 1px solid #ddd;
        background-color: #007bff;
        color: white;
        font-size: 16px;
        padding: 4px 10px;
        cursor: pointer;
        border-radius: 4px;
        transition: 0.3s ease;
    }

    .quantity-control button:hover {
        background-color: #0056b3;
    }

    .quantity-control input {
        text-align: center;
        border: 1px solid #ddd;
        padding: 3px;
        font-size: 14px;
        border-radius: 4px;
        width: 60px;
    }

    .btn-link {
        color: #007bff;
        text-decoration: underline;
        font-size: 14px;
        cursor: pointer;
        margin: 10px 0;
    }

    .btn-link:hover {
        color: #0056b3;
    }

    .submit-btn {
        background-color: #007bff;
        color: #fff;
        border: none;
        padding: 10px 15px;
        border-radius: 4px;
        cursor: pointer;
        transition: 0.3s ease;
        width: 100%;
        margin-top: 20px;
    }

    .submit-btn:hover {
        background-color: #0056b3;
    }

    .item-description,
    .item-stock {
        font-size: 14px;
        color: #555;
        margin-top: 5px;
    }

    .item-description span,
    .item-stock span {
        font-weight: 600;
        color: #333;
    }
</style>