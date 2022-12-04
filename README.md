

## About Project

A Commission Report page that shows a report of all orders that are in the database built in Laravel and blade. It was built mainly to practice how to query and optimize large data from the database.

- Invoice: Invoice number of the order.
- Purchaser: Purchaser of the order.
- Distributor: The person who referred the Purchaser. Also known as the Referrer. If the Purchaser’s referrer is not a Distributor, leave this column blank.
- Referred Distributors: The number of Distributors that the Referrer/Distributor (from the 3rd column) has referred by the time the order was made. Note that this       number gradually increases as the Referrer refers more Distributors over time.
- Order Date: Date the order was placed.
- Percentage: Commission percentage that the Distributor (Referrer) will get paid. Note that this is dependent on the number of Referred Distributors he/she has. If     the purchaser is not a Customer, the Distributor does not earn commission.
- Commission: Percentage x Order Total.


## Note
Please request for Database Schema
