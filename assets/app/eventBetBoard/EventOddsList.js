import React, {Component, Fragment} from 'react';

class EventOddsList extends Component {

    render() {
            return (
                this.props.oddsListData[0].listOfOdds.map((row, index) => (
                    <Odds row={[this.props.oddsListData[0].id, index, row, this.props.oddsListData[1], this.props.oddsListData[0].typeOfBet.name]} key={index}
                          addOddsToCart={this.props.addOddsToCart}
                          removeOddsFromBetBoard={this.props.removeOddsFromBetBoard}
                    />
                ))
            );

    }
}

class Odds extends Component {
    constructor(props) {
        super(props);
        this.state = {
            selected: false,
            buttonColor: "btn-custom",
            iconDisplay: "hide fa fa",
            oddsDisplay: null,
            textDisplay: "hide",
            text: "",
            itemId: null,
            betId: this.props.row[0],
            oddsIndex: this.props.row[1],
            loading: false,
        };
    }

    componentDidMount() {
        if (!this.state.loading) {
            this.displayButtonGreen();
        }
    }


    displayButtonGreen = () => {
        if (this.props.row[3].length > 0) {
            this.props.row[3].forEach(item => {
                if (this.props.row[0] === item[0] && this.props.row[1] === item[1]) {
                    console.log('button green', this.props.row, item)
                    this.setState({
                        selected: true,
                        buttonColor: "btn-custom success",
                        iconDisplay: "fa fa-check",
                        oddsDisplay: null,
                        itemId: item[2],
                    })
                }
            })
        }

        this.setState({loading: true})
    }

    selectedOdds = () => {
        if (!this.state.selected) {
            this.setState({
                selected: true,
                buttonColor: "btn-custom",
                iconDisplay: "hide fa fa-check",
                oddsDisplay: "hide",
                textDisplay: "divTrans btn-successOnClick",
                text: "Ajout??",
                loading: true
            })
            this.props.addOddsToCart([this.state.betId, this.state.oddsIndex]);
        } else {
            this.setState({
                selected: false,
                buttonColor: "btn-custom",
                iconDisplay: "hide fa fa-check",
                oddsDisplay: "hide",
                textDisplay: "divTrans btn-dangerOnClick",
                text: "Supprim??",
                loading: true
            })
            this.props.removeOddsFromBetBoard(this.state.itemId);
        }
    }


    render() {
            if( (this.state.loading && this.props.row[4] === '1N2')  || (this.state.loading && this.props.row[4] === '1-2')){
                return (
                    <td className="text-center" style={{width: "20%"}}>
                        <button className={this.state.buttonColor} role="button" aria-pressed="true"
                                onClick={this.selectedOdds}>
                            <i className={this.state.iconDisplay}/>
                            <em className={this.state.oddsDisplay}>
                                {this.props.row[2][1]}
                            </em>
                            <div className={this.state.textDisplay}>
                                <div className="divText" data-textadded="Ajout??"
                                     data-textremoved="Supprim??">{this.state.text}</div>

                            </div>

                        </button>
                    </td>
                );
            }
            if(this.state.loading && this.props.row[4] === 'over-under'){
                return (
                    <div className="col-6" style={{marginTop :'5px' }}>
                    <button className={this.state.buttonColor} role="button" aria-pressed="true"
                                onClick={this.selectedOdds}>
                            <i className={this.state.iconDisplay}/>
                            <em className={this.state.oddsDisplay}>
                                {this.props.row[2][0]} - {this.props.row[2][1]}
                            </em>
                            <div className={this.state.textDisplay}>
                                <div className="divText" data-textadded="Ajout??"
                                     data-textremoved="Supprim??">{this.state.text}</div>

                            </div>

                        </button>
                    </div>
                );
            }
        if(this.state.loading && this.props.row[4] === 'score exact'){
                    return (
                        <div className="col-4" style={{marginTop :'5px' }}>
                            <button className={this.state.buttonColor} role="button" aria-pressed="true"
                                    onClick={this.selectedOdds}>
                                <i className={this.state.iconDisplay}/>
                                <em className={this.state.oddsDisplay}>
                                    {this.props.row[2][0]} - {this.props.row[2][1]}
                                </em>
                                <div className={this.state.textDisplay}>
                                    <div className="divText" data-textadded="Ajout??"
                                         data-textremoved="Supprim??">{this.state.text}</div>

                                </div>

                            </button>
                        </div>
                    );
                }
        if(this.state.loading && this.props.row[4] === 'mi-temps fin de match'){
            return (
                <div className="col-12" style={{marginTop :'5px' }}>
                    <button className={this.state.buttonColor} role="button" aria-pressed="true"
                            onClick={this.selectedOdds}>
                        <i className={this.state.iconDisplay}/>
                        <em className={this.state.oddsDisplay}>
                            {this.props.row[2][0]} - {this.props.row[2][1]}
                        </em>
                        <div className={this.state.textDisplay}>
                            <div className="divText" data-textadded="Ajout??"
                                 data-textremoved="Supprim??">{this.state.text}</div>

                        </div>

                    </button>
                </div>
            );
        }

        else {
            return (
                <Fragment>

                </Fragment>
            )
        }


    }
}

export default EventOddsList;
