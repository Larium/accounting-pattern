# Payment demo application

Payment process and Account handling using [Accounting Patterns](http://martinfowler.com/eaaDev/).

The concept is that a Payment provider process a Payment Transction, on behalf of a Merchant.

Payment provider MUST stores the Transaction and MUST creates [Account Entries](http://martinfowler.com/eaaDev/AccountingEntry.html) for each of the participants.

[Account Entries](http://martinfowler.com/eaaDev/AccountingEntry.html) are creates through an [Accounting Transaction](http://martinfowler.com/eaaDev/AccountingTransaction.html).

All entries are created through an Event Dispatcher after Payment was created.