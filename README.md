# laravel-docker-template


・購入後の画面遷移について
カード支払いはStripe決済完了後、注文情報を保存します。
コンビニ支払いはStripe Checkoutの支払い手順画面へ遷移します。
コンビニ支払い完了後の自動更新はWebhookで対応可能です。