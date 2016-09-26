FORMAT: 1A

# PURCHASE-ORDER

# Purchase Orders [/purchase/orders]
Purchase Order resource representation.

## Show all purchase/orders [GET /purchase/orders]


+ Request (application/json)
    + Body

            {
                "search": {
                    "_id": "string",
                    "issuedby": "string",
                    "issuedto": "string",
                    "issuedat": "string"
                },
                "sort": {
                    "newest": "asc|desc",
                    "issedby": "desc|asc",
                    "issuedto": "desc|asc",
                    "issuedat": "desc|asc"
                },
                "take": "integer",
                "skip": "integer"
            }

+ Response 200 (application/json)
    + Body

            {
                "status": "success",
                "data": {
                    "data": {
                        "_id": "string",
                        "code": "string",
                        "issued_by": {
                            "type": "string",
                            "identifier": "string",
                            "name": "string"
                        },
                        "issued_at": "datetime",
                        "issued_to": {
                            "type": "string",
                            "identifier": "string",
                            "name": "string"
                        },
                        "products": {
                            "description": "string",
                            "code": "string",
                            "price": {
                                "gross": "number",
                                "net": "number",
                                "discount": "number"
                            },
                            "quantity": "number"
                        },
                        "expenses": {
                            "desc": "string",
                            "subtitle": "true"
                        }
                    },
                    "count": "integer"
                }
            }

## Store PurchaseOrder [POST /purchase/orders]


+ Request (application/json)
    + Body

            {
                "_id": "null",
                "code": "string",
                "issued_by": {
                    "type": "string",
                    "identifier": "string",
                    "name": "string"
                },
                "issued_at": "datetime",
                "issued_to": {
                    "type": "string",
                    "identifier": "string",
                    "name": "string"
                },
                "products": {
                    "description": "string",
                    "code": "string",
                    "price": {
                        "gross": "number",
                        "net": "number",
                        "discount": "number"
                    },
                    "quantity": "number"
                },
                "expenses": {
                    "desc": "string",
                    "subtitle": "true"
                }
            }

+ Response 200 (application/json)
    + Body

            {
                "status": "success",
                "data": {
                    "_id": "string",
                    "code": "string",
                    "issued_by": {
                        "type": "string",
                        "identifier": "string",
                        "name": "string"
                    },
                    "issued_at": "datetime",
                    "issued_to": {
                        "type": "string",
                        "identifier": "string",
                        "name": "string"
                    },
                    "products": {
                        "description": "string",
                        "code": "string",
                        "price": {
                            "gross": "number",
                            "net": "number",
                            "discount": "number"
                        },
                        "quantity": "number"
                    },
                    "expenses": {
                        "desc": "string",
                        "subtitle": "true"
                    }
                }
            }

+ Response 200 (application/json)
    + Body

            {
                "status": {
                    "error": [
                        "code must be unique."
                    ]
                }
            }

## Delete PurchaseOrder [DELETE /purchase/orders]


+ Request (application/json)
    + Body

            {
                "id": null
            }

+ Response 200 (application/json)
    + Body

            {
                "status": "success",
                "data": {
                    "_id": "string",
                    "code": "string",
                    "issued_by": {
                        "type": "string",
                        "identifier": "string",
                        "name": "string"
                    },
                    "issued_at": "datetime",
                    "issued_to": {
                        "type": "string",
                        "identifier": "string",
                        "name": "string"
                    },
                    "products": {
                        "description": "string",
                        "code": "string",
                        "price": {
                            "gross": "number",
                            "net": "number",
                            "discount": "number"
                        },
                        "quantity": "number"
                    },
                    "expenses": {
                        "desc": "string",
                        "subtitle": "true"
                    }
                }
            }

+ Response 200 (application/json)
    + Body

            {
                "status": {
                    "error": [
                        "code must be unique."
                    ]
                }
            }